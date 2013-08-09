<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\ChainAction;

class ChainActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new ChainAction();
        $action->setup($options);
        return $action;
    }

}