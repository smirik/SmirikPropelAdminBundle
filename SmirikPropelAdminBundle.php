<?php

namespace Smirik\PropelAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Smirik\PropelAdminBundle\DependencyInjection\Compiler\PropelAdminGridCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SmirikPropelAdminBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PropelAdminGridCompilerPass());
    }

}
