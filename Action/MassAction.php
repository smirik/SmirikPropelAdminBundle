<?php

namespace Smirik\PropelAdminBundle\Action;

/**
 * @todo complete refactoring
 */
class MassAction implements ActionInterface
{
	
	public $name;
	public $route;
	public $checked;
	public $alert;
	public $flush_checkboxes;
	public $with_id;

	public function __construct($name, $route, $checked = array(), $alert = '', $flush_checkboxes = false, $with_id = false)
	{
		$this->name             = $name;
		$this->route            = $route;
		$this->checked          = $checked;
		$this->alert            = $alert;
		$this->flush_checkboxes = $flush_checkboxes;
		$this->with_id          = false;
	}

    public function getAlias()
    {
        return 'mass';
    }
    
    public function getType()
    {
        return 'mass';
    }

    public function getLabel()
	{
		return $this->name;
	}
	
	public function getName()
	{
	  return $this->name;
	}
	
	public function getFlush()
	{
		if ($this->flush_checkboxes)
		{
			return 'true';
		}
		return 'false';
	}
	
	public function getWithId()
	{
		return $this->with_id;
	}
	
}
