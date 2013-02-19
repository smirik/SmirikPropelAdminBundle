<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\SingleAction;

class SingleActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new SingleAction();
        $action->setup($options);
        return $action;
    }

}