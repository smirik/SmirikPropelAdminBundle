<?php

namespace Smirik\PropelAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class PropelAdminGridCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('admin.action.manager')) {
            return;
        }

        $this->processAction($container);
        $this->processColumn($container);

    }

    private function processAction($container)
    {
        $definition = $container->getDefinition(
            'admin.action.manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'smirik_propel_admin.action.builder'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addBuilder',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }

    private function processColumn($container)
    {
        $definition = $container->getDefinition(
            'admin.column.manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'smirik_propel_admin.column.builder'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addBuilder',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }

}