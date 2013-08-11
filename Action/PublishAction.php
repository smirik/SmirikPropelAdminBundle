<?php

namespace Smirik\PropelAdminBundle\Action;

class PublishAction extends ObjectAction
{
    
    protected $keys = array('template');
    protected $required_keys = array('name', 'label', 'route', 'options', 'data');
	
    protected $data;
    
    public function getAlias()
    {
        return 'publish';
    }
    
    public function getType()
    {
        return 'object';
    }
    
    public function getView($item)
    {
        if ($item->isPublished())
        {
            return $this->data[1]['text'];
        }
        return $this->data[0]['text'];
    }
    
}