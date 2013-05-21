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

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use FOS\UserBundle\Model\UserManagerInterface,
    FOS\UserBundle\Mailer\MailerInterface,
    FOS\UserBundle\Util\TokenGenerator;

use HWI\Bundle\OAuthBundle\Form\FOSUBRegistrationFormHandler as BaseClass,
    HWI\Bundle\OAuthBundle\OAuth\Response\AdvancedUserResponseInterface,
    HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function process(Request $request, Form $form, UserResponseInterface $userInformation)
    {
        // if the form is not posted we'll try to set some properties
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                return true;
            }
        } else {
            $user = $form->getData();
            $user->setUsername($this->getUniqueUsername($userInformation->getNickname()));
            if ($userInformation instanceof AdvancedUserResponseInterface && method_exists($user, 'setEmail')) {
                $user->setEmail($userInformation->getEmail());
            }
            if($userInformation instanceof UserResponseInterface) {
                $paths = $userInformation->getPaths();
                foreach($paths as $path=>$value) {
                   $setMethod = 'set'.ucfirst($path);
                   $getMethod = 'get'.ucfirst($path);

                    if(method_exists($user, $setMethod) &&
                        !in_array($path, array('facebookUid', 'facebookName', 'facebookData',
                                                'twitterUid', 'twitterName', 'twitterData',
                                                'gplusUid', 'gplusName', 'gplusData',
                                                'linkedinUid', 'linkedinName', 'linkedinData'))) {
                       try {
                           $user->$setMethod($userInformation->$getMethod($path));
                       } catch (AuthenticationException $e) {
                           throw $e;
                       }
                   }
                }
            }
            $form->setData($user);
        }

        return false;
    }
}