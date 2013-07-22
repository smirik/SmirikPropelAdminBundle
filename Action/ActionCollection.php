<?php

namespace Smirik\PropelAdminBundle\Action;

class ActionCollection implements \IteratorAggregate, \Countable
{
	
	/**
	 * @var array $actions --- Array of Action
	 */
	protected $actions = array();
	
	/**
	 * Check is there action with given name
	 * @method has
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return array_key_exists($key, $this->actions);
	}

    /**
     * Get all actions created by $alias builder
     * @param string $alias
     * @return array
     */
    public function getByAlias($alias)
    {
        $actions = array();
        foreach ($this->actions as $action)
        {
            if ($action->getAlias() == $alias) {
                $actions[] = $action;
            }
        }
        return $actions;
    }

    /**
     * Get all actions by type
     * @param $type
     * @internal param string $alias
     * @return array
     */
    public function getByType($type)
    {
        $actions = array();
        foreach ($this->actions as $action)
        {
            if ($action->getType() == $type) {
                $actions[] = $action;
            }
        }
        return $actions;
    }
	
	public function getIterator()
	{
		return new \ArrayIterator($this->actions);
	}
	
	public function setActions($actions)
	{
		$this->actions = $actions;
	}
	
	public function getActions()
	{
		return $this->actions;
	}
	
	public function addAction($action)
	{
		$this->actions[] = $action;
	}

    public function count()
    {
        return count($this->actions);
    }
}