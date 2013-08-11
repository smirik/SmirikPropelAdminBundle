<?php

namespace Smirik\PropelAdminBundle\Builder\Action;

use Smirik\PropelAdminBundle\Action\PublishAction;

class PublishActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new PublishAction();
        $action->setup($options);
        return $action;
    }

}