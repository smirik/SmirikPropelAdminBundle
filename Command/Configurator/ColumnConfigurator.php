<?php
    
namespace Smirik\PropelAdminBundle\Command\Configurator;

use Smirik\PropelAdminBundle\Column\ColumnCollection;
use Smirik\PropelAdminBundle\Column\ColumnInterface;

class ColumnConfigurator 
{
    
    protected $create_form;
    protected $column_collection;
    protected $form_collection;

    public function __construct()
    {
        $this->column_collection = new ColumnCollection();
        $this->form_collection   = new ColumnCollection();
    }
    
    public function addColumn(ColumnInterface $column)
    {
        $this->column_collection->addColumn($column);
        if ($this->getCreateForm() && $column->isEditable()) {
            $this->form_collection->addColumn($column);
        }
    }
    
    public function toArray()
    {
        $columns = array();
        foreach ($this->column_collection as $column)
        {
            $columns[$column->getName()] = array(
                'name' => $column->getName(),
                'label' => $column->getLabel(),
                'type' => $column->getType(),
                'builder' => 'simple',
                'options' => $column->getOptions(),
            );
        }
        return $columns;
    }

    public function formPlaceholder($content, $controller_configurator)
    {
        $tmp           = explode("\\", $controller_configurator->getForm());
        $form_filename = $tmp[count($tmp) - 1];

        $content = str_replace('**Controller**', $controller_configurator->getController(), $content);
        $content = str_replace('**Model**', $controller_configurator->getModel(), $content);
        $content = str_replace('**ModelName**', $controller_configurator->getModelName(), $content);
        $content = str_replace('**FormName**', $form_filename, $content);
        $content = str_replace('**FIELDS**', $this->formToArray(), $content);
        return $content;
    }
    
    public function formToArray()
    {
        $columns = array();
        foreach ($this->form_collection as $column)
        {
            $columns[] = '      ->add(\''.$column->getName().'\')';
        }
        return implode("\n      ", $columns);
    }

	public function setCreateForm($create_form)
	{
		$this->create_form = $create_form;
	}

	public function getCreateForm()
	{
		return $this->create_form;
	}

	public function setColumnCollection($column_collection)
	{
		$this->column_collection = $column_collection;
	}

	public function getColumnCollection()
	{
		return $this->column_collection;
	}

	public function setFormCollection($form_collection)
	{
		$this->form_collection = $form_collection;
	}

	public function getFormCollection()
	{
		return $this->form_collection;
	}

}