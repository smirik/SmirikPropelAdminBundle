<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\AjaxFlagAction;

class AjaxFlagActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new AjaxFlagAction();
        $action->setup($options);
        return $action;
    }

}