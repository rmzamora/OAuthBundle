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

use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;

use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent,
    Symfony\Component\Security\Http\SecurityEvents,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Security\Core\Exception\AccountStatusException;

use FOS\UserBundle\Model\UserInterface;
use Rmzamora\SonataExt\UserBundle\Controller\RegistrationController as BaseClass;

class RegistrationController extends BaseClass
{
    public function completeRegistrationAction(Request $request, $key)
    {
        $hasUser = $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        $session = $request->getSession();
        $error = $session->get('_hwi_oauth.registration_error.'.$key);
        $session->remove('_hwi_oauth.registration_error.'.$key);

        if ($hasUser || !($error instanceof AccountNotLinkedException) || (time() - $key > 800)) {
            // todo: fix this
            throw new \Exception('Cannot register an account.');
        }

        $userInformation = $this->getResourceOwnerByName($error->getResourceOwnerName())->getUserInformation($error->getAccessToken());
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $form = $this->container->get('hwi_oauth.registration.form.factory')->createForm();
        $form->setData($user);

        /**
         * TODO: cleanup form processing, eventually remove form handler
         */
        $formHandler = $this->container->get('rmzamora_oauth.registration_complete.form.handler');

        if ($formHandler->process($request, $form, $userInformation)) {
            try {

                $this->container->get('hwi_oauth.user.provider.fosub_bridge')->connect($form->getData(), $userInformation);
                $route = 'fos_user_registration_confirmed';
                $url = $this->container->get('router')->generate($route);
                $response = new RedirectResponse($url);
                // Authenticate the user
                $this->authenticateUser($form->getData(), $response);
                return $response;

            } catch (\Exception $e) {
                throw $e;
            }
        }

        // reset the error in the session
        $key = time();
        $session->set('_hwi_oauth.registration_error.'.$key, $error);

        return $this->container->get('templating')->renderResponse('RmzamoraOAuthBundle:Registration:register_oauth.html.twig', array(
            'key' => $key,
            'form' => $form->createView(),
            'userInformation' => $userInformation,
        ));
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param UserInterface $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('hwi_oauth.user_checker')->checkPostAuth($user);
        } catch (AccountStatusException $e) {
            // Don't authenticate locked, disabled or expired users
            return;
        }

        $providerKey = $this->container->getParameter('hwi_oauth.firewall_name');
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.context')->setToken($token);

        // Since we're "faking" normal login, we need to throw our INTERACTIVE_LOGIN event manually
        $this->container->get('event_dispatcher')->dispatch(
            SecurityEvents::INTERACTIVE_LOGIN,
            new InteractiveLoginEvent($this->container->get('request'), $token)
        );
    }

    /**
     * Get a resource owner by name.
     *
     * @param string $name
     *
     * @return ResourceOwnerInterface
     *
     * @throws \RuntimeException if there is no resource owner with the given name.
     */
    protected function getResourceOwnerByName($name)
    {
        $ownerMap = $this->container->get('hwi_oauth.resource_ownermap.'.$this->container->getParameter('hwi_oauth.firewall_name'));

        if (null === $resourceOwner = $ownerMap->getResourceOwnerByName($name)) {
            throw new \RuntimeException(sprintf("No resource owner with name '%s'.", $name));
        }

        return $resourceOwner;
    }
}
