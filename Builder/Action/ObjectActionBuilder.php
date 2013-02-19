<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\ObjectAction;

class ObjectActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new ObjectAction();
        $action->setup($options);
        return $action;
    }

}