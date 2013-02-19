<?php

namespace Smirik\PropelAdminBundle\Column;

class ColumnCollection implements \IteratorAggregate
{
	
	/**
	 * @var array $coumns --- array of Column
	 */
	protected $columns = array();
	
	/**
	 * Check is there column with given name
	 * @method has
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return array_key_exists($key, $this->columns);
	}
	
	public function getIterator()
	{
		return new \ArrayIterator($this->columns);
	}
	
	public function setColumns($columns)
	{
		$this->columns = $columns;
	}
	
	public function getColumns()
	{
		return $this->columns;
	}
	
	public function addColumn($column)
	{
		$this->columns[] = $column;
	}

}