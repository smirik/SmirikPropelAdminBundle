<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\AjaxObjectAction;

class AjaxObjectActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new AjaxObjectAction();
        $action->setup($options);
        return $action;
    }

}