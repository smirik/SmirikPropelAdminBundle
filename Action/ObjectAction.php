<?php

namespace Smirik\PropelAdminBundle\Action;

class ObjectAction extends Action
{
    
    protected $keys = array('template');
    protected $required_keys = array('name', 'label', 'route', 'options');
	
    public function getAlias()
    {
        return 'object';
    }
    
    public function getType()
    {
        return 'object';
    }

}