<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

interface ActionBuilderInterface
{

    /**
     * Creates new action related to the options parameters. Returns created Action
     * @param array $options
     * @return object
     */
    function create($options);

}