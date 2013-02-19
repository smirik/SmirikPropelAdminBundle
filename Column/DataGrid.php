<?php

namespace Smirik\PropelAdminBundle\Column;

use Smirik\PropelAdminBundle\Action\ActionCollection;

class DataGrid implements \IteratorAggregate
{

    /**
     * List of all columns
     * @var array $columns
     */
    protected $columns;

    /**
     * List of actions with columns
     * @var $actions
     */
    protected $actions;

    protected $templates = array(
        'form' => array(
            'edit'   => 'SmirikPropelAdminBundle:Admin/Form:edit.html.twig',
            'new'    => 'SmirikPropelAdminBundle:Admin/Form:new.html.twig',
            'fields' => 'SmirikPropelAdminBundle:Admin/Form:fields.html.twig',
        ),
        'list' => array(
            'mass_actions'   => 'SmirikPropelAdminBundle:Admin/List:mass_actions.html.twig',
            'paginate'       => 'SmirikPropelAdminBundle:Admin/List:paginate.html.twig',
            'single_actions' => 'SmirikPropelAdminBundle:Admin/List:single_actions.html.twig',
            'table_filters'  => 'SmirikPropelAdminBundle:Admin/List:table_filters.html.twig',
            'table_header'   => 'SmirikPropelAdminBundle:Admin/List:table_header.html.twig',
        ),
        'index'         => 'SmirikPropelAdminBundle:Admin:index.html.twig',
        'index_content' => 'SmirikPropelAdminBundle:Admin:index_content.html.twig',
        'row'           => 'SmirikPropelAdminBundle:Admin:row.html.twig',
    );

    /**
     * Show options
     */
    protected $is_sortable = false;
    protected $is_filterable = false;

    protected $limit = 15;

    public function __construct()
    {
        $this->actions = new ActionCollection();
        $this->columns = new ColumnCollection();
    }

    /**
     * Loads & setup actions & columns config. 
     * @param array $config
     * @param Smirik\PropelAdminBundle\Manager\ColumnManager $column_manager
     * @param Smirik\PropelAdminBundle\Manager\ActionManager $action_manager
     * @return void
     */
    public function load($config, $column_manager, $action_manager)
    {
        /**
         * Load columns & actions
         */
        $columns = array();
        foreach ($config['columns'] as $key => $column) {
            $columns[$key] = $column_manager->create($column);
        }
        $this->columns->setColumns($columns);

        $actions = array();
        foreach ($config['actions'] as $key => $action) {
            $actions[$key] = $action_manager->create($action);
        }
        $this->actions->setActions($actions);
        
        /**
         * Merge custom templates
         */
        if (isset($config['templates'])) {
            foreach ($this->templates as $key => $array)
            {
                if (isset($config['templates'][$key]))
                {
                    if (is_array($array))
                    {
                        $this->templates[$key] = $config['templates'][$key] + $this->templates[$key];
                    } else
                    {
                        $this->templates[$key] = $config['templates'][$key];
                    }
                }
            }
        }
        
    }
    
    /**
     * Get template related to relative name
     * @param string $name
     * @return string
     */
    public function template($name)
    {
        /**
         * @todo refactoring
         */
        $tmp = explode('.', $name);
        $num = count($tmp);
        if ($num == 1)
        {
            return $this->templates[$name];
        } elseif ($num == 2)
        {
            if (!isset($this->templates[$tmp[0]][$tmp[1]]))
            {
                throw new \Exception('Template not found');
            }
            return $this->templates[$tmp[0]][$tmp[1]];
        }
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->columns);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

}