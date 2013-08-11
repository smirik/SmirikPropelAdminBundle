<?php

namespace Smirik\PropelAdminBundle\Manager;

use Symfony\Component\Finder\Finder;

class ColumnManager extends BuilderManager
{

    /**
     * @param ActionBuilderInterface $builder
     * @param void
     */
    public function addBuilder(\Smirik\PropelAdminBundle\Builder\Column\ColumnBuilderInterface $builder, $alias)
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
    public function getStandard()
    {
        $kernel = $this->container->get('kernel');
        $path   = $kernel->locateResource('@SmirikPropelAdminBundle/Column/Template/');

        $finder = new Finder();
        $finder->files()->in($path);
        return $finder;
    }
    
}