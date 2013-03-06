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

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseClass;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;

class BaseRegistrationFormHandler extends BaseClass
{
    /**
     * @param boolean $confirmation
     */
    public function process($confirmation = false)
    {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    /**
     * @param boolean $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        if ($confirmation) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
        }

        $this->userManager->updateUser($user);
    }
}
