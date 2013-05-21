<?php

namespace Rmzamora\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rmzamora\OAuthBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class RmzamoraOAuthBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'RmzamoraSonataExtUserBundle';
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
