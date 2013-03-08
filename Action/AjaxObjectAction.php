<?php

namespace Smirik\PropelAdminBundle\Action;

class AjaxObjectAction extends ObjectAction
{

    /**
     * @var string $getter --- model method
     */
    protected $getter;

    /**
     * @var array $data --- attached data from config
     */
    protected $data;

    /**
     * @var array $keys --- list of additional keys in options
     */
    protected $keys  = array();

    /**
     * @var array $required_keys --- list of required keys in options
     */
    protected $required_keys = array('name', 'label', 'route', 'options', 'getter', 'data');

    public function getValue($obj)
    {
        $getter = $this->getter;
        return $obj->$getter();
    }
    
    public function getGetter()
    {
        return $this->getter;
    }
    
    public function getView($obj)
    {
        $value = $this->getValue($obj);
        return $this->data[$value]['text'];
    }
    
    public function getJson()
    {
        return json_encode($this->data);
    }

    public function getAlias()
    {
        return 'ajax_object';
    }

}
