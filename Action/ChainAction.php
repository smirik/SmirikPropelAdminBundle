<?php

namespace Smirik\PropelAdminBundle\Action;

class ChainAction extends ObjectAction
{
    
    protected $template = 'SmirikPropelAdminBundle:Admin/Action:chain.html.twig';
        
    /**
     * 
     */
    protected $required_keys = array('name', 'label', 'route', 'options', 'getter', 'setter', 'data');
    
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
        return 'chain';
    }
    
    /**
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }
    
    /**
     * Find position of object in data array according to key.
     * @param object
     * @return integer
     */
    private function getPositionByKey($obj)
    {
        $getter = $this->getter;
        $current_value = (int)$obj->$getter();
        $count = 0;
        foreach ($this->data as $data)
        {
            if ($data['key'] == $current_value)
            {
                return $count;
            }
            $count++;
        }
        throw new \Exception('Data for key = '.$obj->$getter().' is not specified');
    }
    
    public function getNextValue($obj)
    {
        $position = $this->getPositionByKey($obj);
        if (isset($this->data[$position+1]))
        {
            return $this->data[$position+1]['key'];
        }
        return $this->data[0]['key'];
    }
    
    public function getView($obj)
    {
        $value = $this->getPositionByKey($obj);
        return $this->data[$value]['text'];
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getJson()
    {
        return json_encode($this->data);
    }
    
    public function getGetter()
    {
        return $this->getter;
    }
    
}
