<?php

namespace Smirik\PropelAdminBundle\Action;

class SimpleAction extends Action
{

    public function getAlias()
    {
        return 'simple';
    }
    
    public function getType()
    {
        return 'object';
    }

}