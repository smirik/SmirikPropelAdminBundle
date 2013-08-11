<?php
    
namespace Smirik\PropelAdminBundle\Command\Configurator;

class ControllerConfigurator 
{
    
    protected $controller;
    protected $model_name;
    protected $model;
    protected $query;
    protected $form;
    protected $form_filename;
    protected $admin_prefix;
    protected $url_prefix;
    protected $bundle;
    protected $layout;
    
    public function placeholder($content)
    {
        $content = str_replace('**Controller**', $this->getController(), $content);
        $content = str_replace('**ModelName**', $this->getModelName(), $content);
        $content = str_replace('**layout**', $this->getLayout(), $content);
        $content = str_replace('**url_prefix**', $this->getUrlPrefix(), $content);
        $content = str_replace('**Query**', '\\'.$this->getQuery(), $content);
        $content = str_replace('**Form**', $this->getForm(), $content);
        $content = str_replace('**Model**', '\\'.$this->getModel(), $content);
        $content = str_replace('**bundle**', $this->getBundle(), $content);
        return $content;
    }

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	public function getController()
	{
		return $this->controller;
	}

	public function setModelName($model_name)
	{
		$this->model_name = $model_name;
	}

	public function getModelName()
	{
		return $this->model_name;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setQuery($query)
	{
		$this->query = $query;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function setForm($form)
	{
		$this->form = $form;
        $tmp = explode("\\", $form);
        $this->setFormFilename($tmp[count($tmp) - 1]);
	}

	public function getForm()
	{
		return $this->form;
	}

	public function setFormFilename($form_filename)
	{
		$this->form_filename = $form_filename;
	}

	public function getFormFilename()
	{
		return $this->form_filename;
	}

	public function setAdminPrefix($admin_prefix)
	{
		$this->admin_prefix = $admin_prefix;
	}

	public function getAdminPrefix()
	{
		return $this->admin_prefix;
	}

	public function setUrlPrefix($model_prefix)
	{
		$this->model_prefix = $model_prefix;
	}

	public function getUrlPrefix()
	{
		return $this->model_prefix;
	}

	public function setBundle($bundle)
	{
		$this->bundle = $bundle;
	}

	public function getBundle()
	{
		return $this->bundle;
	}

	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	public function getLayout()
	{
		return $this->layout;
	}

}