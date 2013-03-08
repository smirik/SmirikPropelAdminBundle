<?php

namespace Smirik\PropelAdminBundle\Action;

class AjaxFlagAction extends AjaxObjectAction
{
    
    protected $template = 'SmirikPropelAdminBundle:Admin/Action:ajax_flag.html.twig';
        
    /**
     * 
     */
    protected $required_keys = array('name', 'label', 'route', 'options', 'getter', 'data', 'setter');
    
    /**
     * Model column representing the flag field. Used in enable action in AdminController.
     * @var string
     */
    protected $setter = "is_active";

    /**
     *
     */
    public function getAlias()
    {
        return 'ajax_flag';
    }
    
    /**
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }
    
    public function getValue($obj)
    {
        $getter = $this->getter;
        $current_value = (int)$obj->$getter();
        if (isset($this->data[$current_value+1]))
        {
            return $current_value+1;
        }
        return 0;
    }
    
    public function getView($obj)
    {
        $value = parent::getValue($obj);
        return $this->data[$value]['text'];
    }
    
    
}
