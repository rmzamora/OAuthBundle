<?php
/**
 * This file is part of the RmzamoraSonataExtBundles Package.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // override user provider
        $container->setParameter('hwi_oauth.authentication.provider.oauth.class', 'Rmzamora\OAuthBundle\Security\Core\Authentication\Provider\OAuthProvider');

        $definition = $container->getDefinition('hwi_oauth.user.provider.fosub_bridge');
        $definition->setClass('Rmzamora\OAuthBundle\Security\Core\User\FOSUBUserProvider');
    }
}