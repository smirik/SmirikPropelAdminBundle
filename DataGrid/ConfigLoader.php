<?php

namespace Smirik\PropelAdminBundle\DataGrid;

use Smirik\PropelAdminBundle\Action\ActionCollection;
use Smirik\PropelAdminBundle\Column\ColumnCollection;

class ConfigLoader
{

    /**
     * @var \Smirik\PropelAdminBundle\Manager\ColumnManager $column_manager
     */
    protected $column_manager;
    /**
     * @var \Smirik\PropelAdminBundle\Manager\ActionManager $column_manager
     */
    protected $action_manager;

    public function setManagers($column_manager, $action_manager)
    {
        $this->column_manager = $column_manager;
        $this->action_manager = $action_manager;
    }

    /**
     * Load config from array into collections
     * Return format:
     * actions -> Smirik\PropelAdminBundle\Action\ActionCollection
     * native_actions -> Smirik\PropelAdminBundle\Action\ActionCollection
     * columns -> Smirik\PropelAdminBundle\Column\ColumnCollection
     * @param  array $config
     * @return array
     */
    public function load($config)
    {
        $columns       = $this->loadColumns($config);
        $actions_array = $this->loadActions($config);

        return array(
            'columns'        => $columns,
            'actions'        => $actions_array['actions'],
            'native_actions' => $actions_array['native_actions'],
        );
    }

    private function loadColumns($config)
    {
        $columns = new ColumnCollection();
        $columns_array = array();
        foreach ($config['columns'] as $key => $column) {
            $columns_array[$key] = $this->column_manager->create($column);
        }
        $columns->setColumns($columns_array);

        return $columns;
    }

    private function loadActions($config)
    {
        $actions = new ActionCollection();
        $native_actions = new ActionCollection();

        /**
         * Load columns & actions
         */
        $actions_array = array();
        $native_actions_array = array();
        foreach ($config['actions'] as $key => $action) {
            $tmp_action = $this->action_manager->create($action);
            $tmp_action->isNative() ? $native_actions_array[$key] = $tmp_action : $actions_array[$key] = $tmp_action;
        }
        $actions->setActions($actions_array);
        $native_actions->setActions($native_actions_array);

        return array(
            'actions'        => $actions,
            'native_actions' => $native_actions,
        );
    }

}
