<?php

namespace Smirik\PropelAdminBundle\Action;

class AjaxObjectAction extends ObjectAction
{

    /**
     * @var string $method --- model method
     */
    protected $method;

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
    protected $required_keys = array('name', 'label', 'route', 'options', 'method', 'data');

    public function getValue($obj)
    {
        $method = $this->method;
        return $obj->$method();
    }
    
    public function getView($obj)
    {
        $value = $this->getValue($obj);
        return $this->data[$value]['text'];
    }

    public function getAlias()
    {
        return 'ajax_object';
    }

}
