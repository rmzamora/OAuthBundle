<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\Form\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface,
    Symfony\Component\Security\Core\Authentication\Token\TokenInterface,
    Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface,
    Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthAuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            $result = array('success' => true);
            return new Response(json_encode($result));
        } else {
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception){}
}