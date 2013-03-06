<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rmzamora\OAuthBundle\Security\Core\Authentication\Provider;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken,
    HWI\Bundle\OAuthBundle\Security\Core\Exception\OAuthAwareExceptionInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface,
    HWI\Bundle\OAuthBundle\Security\Http\ResourceOwnerMap;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface,
    Symfony\Component\Security\Core\User\UserCheckerInterface,
    Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


use Rmzamora\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;


class OAuthProvider implements AuthenticationProviderInterface
{
    /**
     * @var ResourceOwnerMap
     */
    private $resourceOwnerMap;

    /**
     * @var OAuthAwareUserProviderInterface
     */
    private $userProvider;

    /**
     * @var UserCheckerInterface
     */
    private $userChecker;

    /**
     * @param OAuthAwareUserProviderInterface $userProvider     User provider
     * @param ResourceOwnerMap                $resourceOwnerMap Resource owner map
     * @param UserCheckerInterface            $userChecker      User checker
     */
    public function __construct(OAuthAwareUserProviderInterface $userProvider, ResourceOwnerMap $resourceOwnerMap, UserCheckerInterface $userChecker)
    {
        $this->userProvider     = $userProvider;
        $this->resourceOwnerMap = $resourceOwnerMap;
        $this->userChecker      = $userChecker;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthToken;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {
        $resourceOwner = $this->resourceOwnerMap->getResourceOwnerByName($token->getResourceOwnerName());

        $userResponse = $resourceOwner->getUserInformation($token->getAccessToken());

        try {
            $user = $this->userProvider->loadUserByOAuthUserResponse($userResponse);
        } catch (OAuthAwareExceptionInterface $e) {
            $error =  new AccountNotLinkedException(sprintf("User '%s' not found.", $e->getMessage()));
            $error->setAccessToken($token->getAccessToken());
            $error->setResourceOwnerName($token->getResourceOwnerName());
            $error->setUserResponse($userResponse->getResponse());
            throw $error;
        }

        $token = new OAuthToken($token->getAccessToken(), $user->getRoles());
        $token->setResourceOwnerName($resourceOwner->getName());
        $token->setUser($user);
        $token->setAuthenticated(true);

        $this->userChecker->checkPostAuth($user);

        return $token;
    }
}