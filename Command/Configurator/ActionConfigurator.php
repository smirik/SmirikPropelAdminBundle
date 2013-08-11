<?php
    
namespace Smirik\PropelAdminBundle\Command\Configurator;

class ActionConfigurator 
{

    protected $new;
    protected $edit;
    protected $delete;

    public function toArray($url_prefix)
    {
        $actions = array();
        $actions_names = array('new', 'edit', 'delete');
        foreach ($actions_names as $key) {
            $actions[$key] = array(
                'route'    => 'admin_'.$url_prefix.'_'.$key,
                'extends' => $key,
            );
        }
        return $actions;
    }

	public function setNew($new)
	{
		$this->new = $new;
	}

	public function getNew()
	{
		return $this->new;
	}

	public function setEdit($edit)
	{
		$this->edit = $edit;
	}

	public function getEdit()
	{
		return $this->edit;
	}

	public function setDelete($delete)
	{
		$this->delete = $delete;
	}

	public function getDelete()
	{
		return $this->delete;
	}


}