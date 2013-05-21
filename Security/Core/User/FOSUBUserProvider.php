<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rmzamora\OAuthBundle\Security\Core\User;

use FOS\UserBundle\Model\UserManagerInterface;

use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface,
    HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;

use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response)
    {

        if($response instanceof UserResponseInterface) {
            $paths = $response->getPaths();
            foreach($paths as $path=>$value) {
                $setMethod = 'set'.ucfirst($path);
                $getMethod = 'get'.ucfirst($path);

                if(method_exists($user, $setMethod)) {
                    try {
                        if(null == $user->$getMethod()) {
                           $user->$setMethod($response->$getMethod($path));
                        }
                    } catch (\Exception $e) {
                        throw $e;
                    }
                }
            }
        }
        $user->addRole('ROLE_REGISTERED_USER');
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $getter = 'get'.ucfirst($property);

        if (!method_exists($response, $getter)) {
            throw new \RuntimeException(sprintf("Class '%s' should have a method '%s'.", get_class($response), $getter));
        }

        $user = $this->userManager->findUserBy(array($property => $response->$getter()));

        if (null === $user) {
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $response->$getter()));
        }
        return $user;
    }

    /**
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }
}
