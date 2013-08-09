<?php

namespace Smirik\PropelAdminBundle\Column;

abstract class Column implements ColumnInterface
{

    /**
     * Name-to-show
     * @var string $name
     */
    public $name;

    /**
     * Column technical key
     * @var string $label
     */
    public $label;

    /**
     * @var string
     */
    protected $type;
    
    /**
     * Method related to the data keys in options
     * @var string
     */
    protected $getter;

    /**
     * @var array
     */
    protected $options = array(
        'listable'   => true,
        'editbable'  => true,
        'sortable'   => true,
        'filterable' => true,
    );
    
    /**
     * Default templates to view if nothing other specified in yaml file
     * @var array
     */
    protected $default_templates = array(
        'filter' => 'SmirikPropelAdminBundle:Admin/Filter:%s.html.twig',
        'list'   => 'SmirikPropelAdminBundle:Admin/Column:%s.html.twig',
    );
    protected $templates;

    public function setup($options)
    {
        $keys = array('name', 'label', 'type', 'options', 'templates');

        foreach ($options as $key => $option) {
            if (in_array($key, $keys)) {
                $this->$key = $option;
            }
        }
    }

    /**
     * @method isListable
     * @param none
     * @return boolean
     */
    public function isListable()
    {
        return $this->options['listable'];
    }

    /**
     * @method isEditable
     * @param none
     * @return boolean
     */
    public function isEditable()
    {
        return $this->options['editable'];
    }

    /**
     * @method isSortable
     * @param none
     * @return boolean
     */
    public function isSortable()
    {
        return $this->options['sortable'];
    }

    /**
     * @method isFilterable
     * @param none
     * @return boolean
     */
    public function isFilterable()
    {
        return $this->options['filterable'];
    }

    public function getGetter()
    {
        return \Symfony\Component\DependencyInjection\Container::camelize($this->name);
    }
    
    public function getValue($item)
    {
        $getter = 'get'.$this->getGetter();
        return $item->{$getter}();
    }

    /**
     * @return array
     */
    public function getTemplate($type)
    {
        if (isset($this->templates[$type]))
        {
            return $this->templates[$type];
        }
        return sprintf($this->default_templates[$type], $this->type);
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }
    

}