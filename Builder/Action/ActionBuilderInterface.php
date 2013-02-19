<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

interface ActionBuilderInterface
{

    /**
     * Creates new action related to the options parameters 
     * @param array $options
     * @return void
     */
    function create($options);

}