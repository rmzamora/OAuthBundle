<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\Security\Core\Exception;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException as BaseClass;

class AccountNotLinkedException extends BaseClass
{
    private $accessToken;
    private $resourceOwnerName;
    private $userResponse;


    public function getUserResponse()
    {
        return $this->userResponse;
    }

    public function setUserResponse($userResponse)
    {
        $this->userResponse = $userResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerName()
    {
        return $this->resourceOwnerName;
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceOwnerName($resourceOwnerName)
    {
        $this->resourceOwnerName = $resourceOwnerName;
    }

    public function serialize()
    {
        return serialize(array(
            $this->userResponse,
            $this->accessToken,
            $this->resourceOwnerName,
        ));
    }

    public function unserialize($str)
    {
        list(
            $this->userResponse,
            $this->accessToken,
            $this->resourceOwnerName
            ) = unserialize($str);
    }
}
