<?php

namespace Smirik\PropelAdminBundle\Action;

abstract class Action implements ActionInterface
{

    /**
     * @var string $name --- public name for action
     */
    protected $name;

    /**
     * @var string $label
     */
    public $label;
    
    /**
     * @var array
     */
    public $options = array(
        'route_with_id' => false,
        'confirmation'  => false,
    );

    /**
     * @var string $route
     */
    public $route;

    /**
     * @var boolean $route_with_id --- add id to route?
     */
    public $route_with_id;

    /**
     * @var boolean $confirmation --- js confirmation before action
     */
    protected $confirmation = false;
    
    /**
     * @var string $template -- template for action
     */
    protected $template = false;

    /**
     * @var array $keys --- list of additional keys in options
     */
    protected $keys = array('template', 'options');
    /**
     * @var array $required_keys --- list of required keys in options
     */
    protected $required_keys = array('name', 'label', 'route');

    public function setup($options)
    {
        foreach ($options as $key => $option) {
            if (in_array($key, $this->keys)) {
                $this->$key = $option;
            }
        }
        
        foreach ($this->required_keys as $key)
        {
            if (!array_key_exists($key, $options))
            {
                throw new \Exception('PropelAdminBundle: Required action config '.$key.' not found.');
            }
            $this->$key = $options[$key];
        }
    }

    public function withConfirmation()
    {
        return $this->confirmation;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getTemplate()
    {
        if ($this->template)
        {
            return $this->template;
        }
        return 'SmirikPropelAdminBundle:Admin/Action:'.$this->getAlias().'.html.twig';
    }

}