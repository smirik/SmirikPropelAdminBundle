<?php

namespace Smirik\PropelAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Smirik\PropelAdminBundle\DataGrid\DataGrid;

abstract class AdminAbstractConfigurationController extends Controller
{
    /**
     * @var \Smirik\PropelAdminBundle\DataGrid\DataGrid $grid
     */
    public $grid;

    /**
     * @var integer $page
     */
    protected $page = 1;
    /**
     * @var integer $limit
     */
    protected $limit = 15;

    /**
     * @var string
     */
    public $layout = '::base.html.twig';
    
    /**
     * @var mixed
     */
    public $object;
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    protected $routes = array();

    public function __construct()
    {
        $this->generateRoutes();
    }
    
    public function generateRoutes()
    {
        $this->routes = array(
            'index'  => 'admin_'.$this->name.'_index',
            'list'   => 'admin_'.$this->name.'_list',
            'new'    => 'admin_'.$this->name.'_new',
            'create' => 'admin_'.$this->name.'_create',
            'edit'   => 'admin_'.$this->name.'_edit',
            'update' => 'admin_'.$this->name.'_edit',
            'delete' => 'admin_'.$this->name.'_delete',
            'enable' => 'admin_'.$this->name.'_enable',
        );
    }

    public function loadConfig()
    {
        $tmp    = explode('\\', get_class($this));
        $class  = $tmp[count($tmp) - 1];
        $this->get('admin.data.grid')->load($this->bundle, $class);
    }

    public function setup()
    {
        $this->loadConfig();
        if (array_key_exists('AvalancheImagineBundle', $this->container->getParameter('kernel.bundles'))) {
            $this->get('admin.data.grid')->setupAvalanche();
        }
    }

    public function getPaginate()
    {
        $this->page  = $this->getRequest()->query->get('page', false);
        $this->limit = $this->getRequest()->query->get('limit', false);
        
        if (!$this->limit) {
            $this->limit = $this->get('admin.data.grid')->getLimit();
        }
    }

    public function getObject()
    {
    }

    abstract public function getQuery();

    abstract public function getForm();

    public function initialize()
    {
        $this->setup();
        $this->generateRoutes();
    }

}
