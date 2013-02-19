<?php

namespace Smirik\PropelAdminBundle\Manager;

use Symfony\Component\Finder\Finder;

class ActionManager extends BuilderManager
{

    /**
     * @param \Smirik\PropelAdminBundle\Builder\Action\ActionBuilderInterface $builder
     * @param $alias
     */
    public function addBuilder(\Smirik\PropelAdminBundle\Builder\Action\ActionBuilderInterface $builder, $alias)
    {
        if ($alias) {
            $this->builders[$alias] = $builder;
        } else {
            $this->builders[] = $builder;
        }
    }
    
    /**
     * Loads standard templates
     * @return object
     */
    protected function getStandard()
    {
        $kernel = $this->container->get('kernel');
        $path   = $kernel->locateResource('@SmirikPropelAdminBundle/Action/Template/');

        $finder = new Finder();
        $finder->files()->in($path);
        return $finder;
    }
    
}