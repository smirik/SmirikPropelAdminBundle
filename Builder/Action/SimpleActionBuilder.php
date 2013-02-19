<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\SimpleAction;

class SimpleActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new SimpleAction();
        $action->setup($options);
        return $action;
    }

}