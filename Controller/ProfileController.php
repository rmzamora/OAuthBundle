<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rmzamora\OAuthBundle\Controller;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rmzamora\SonataExt\UserBundle\Controller\ProfileController as BaseClass;

/**
 * This class is inspirated from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form
 *
 */
class ProfileController extends BaseClass
{
    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('sonata.user.profile.form');
        $formHandler = $this->container->get('sonata.user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
        }

        return $this->render('SonataUserBundle:Profile:edit_profile.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}