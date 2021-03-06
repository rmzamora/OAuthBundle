<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rmzamora\OAuthBundle\OAuth\ResourceOwner;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\LinkedinResourceOwner as BaseClass;

class LinkedinResourceOwner extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url'   => 'https://www.linkedin.com/uas/oauth/authenticate',
        'request_token_url'   => 'https://api.linkedin.com/uas/oauth/requestToken',
        'access_token_url'    => 'https://api.linkedin.com/uas/oauth/accessToken',
        'infos_url'           => 'http://api.linkedin.com/v1/people/~:(id,formatted-name)',
        'user_response_class' => '\HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse',
        'realm'               => 'http://api.linkedin.com',
        'scope'               => null
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier' => 'id',
        'nickname'   => 'formattedName',
        'realname'   => 'formattedName',
    );

    /**
     * {@inheritDoc}
     */
    protected function httpRequest($url, $content = null, $parameters = array(), $headers = array(), $method = null)
    {
        $headers[] = 'x-li-format: json';

        return parent::httpRequest($url, $content, $parameters, $headers, $method);
    }

    /**
     * Add scope (Required by linkedin API if email address is needed)
     *
     * {@inheritDoc}
     */
    protected function getRequestToken($redirectUri, array $extraParameters = array())
    {
        $extraParameters['scope'] = $this->getOption('scope');

        return parent::getRequestToken($redirectUri, $extraParameters);
    }
}
