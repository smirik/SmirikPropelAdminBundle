<?php

namespace Smirik\PropelAdminBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Smirik\PropelAdminBundle\Column\DataGrid;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AdminAbstractController extends Controller
{
    /**
     * @var \Smirik\PropelAdminBundle\Column\DataGrid $grid
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
        $this->grid = new Datagrid();
    }

    public function loadConfig()
    {
        $tmp    = explode('\\', get_class($this));
        $class  = $tmp[count($tmp) - 1];
        $kernel = $this->get('kernel');
        $path   = $kernel->locateResource('@'.$this->bundle.'/Resources/config/PropelAdmin/'.$class.'.yml');

        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents($path));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        return $value;
    }

    public function setup()
    {
        $yaml           = $this->loadConfig();
        $action_manager = $this->get('admin.action.manager');
        $column_manager = $this->get('admin.column.manager');
        $this->grid->load($yaml, $column_manager, $action_manager);
        if (array_key_exists('AvalancheImagineBundle', $this->container->getParameter('kernel.bundles'))) {
            $this->grid->setupAvalanche();
        }
    }

    public function getPaginate()
    {
        $this->page  = $this->getRequest()->query->get('page', false);
        $this->limit = $this->getRequest()->query->get('limit', false);

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

    public function indexAction($page = false)
    {
        $this->setup();
        $this->getPaginate();
        if (!$this->limit) {
            $this->limit = $this->grid->getLimit();
        }

        $this->page = (int)$page;
        $this->generateRoutes();

        $sort      = $this->getRequest()->query->get('sort', 'id');
        $sort_type = $this->getRequest()->query->get('sort_type', 'desc');
        $filter    = $this->getRequest()->query->get('filter', false);
        $options   = $this->getRequest()->query->get('options', false);
        if ($options) {
            $options = json_decode($options);
        }

        $method = 'orderById';
        if ($sort) {
            $method = (string)'orderBy'.$this->underscore2Camelcase($sort);
        }

        $collection_query = $this->getQuery()
            ->_if($method)
            ->$method($sort_type)
            ->_endIf();

        if ($filter && is_array($filter)) {
            foreach ($filter as $key => $value) {
                if ($value === '') {
                    continue;
                }
                $filter_method = (string)'filterBy'.$this->underscore2Camelcase($key);
                $int_value     = (int)$value;
                if ((string)$int_value != $value) {
                    $value = '%'.$value.'%';
                }
                $collection_query
                    ->$filter_method($value);
            }
        } else {
            $filter = false;
        }

        $collection = $collection_query
            ->paginate($this->page, $this->limit);

        $ajax = false;

        $array = array(
            'collection' => $collection,
            'page'       => $this->page,
            'limit'      => $this->limit,
            'columns'    => $this->grid->getColumns(),
            'layout'     => $this->layout,
            'actions'    => $this->grid->getActions(),
            'routes'     => $this->routes,
            'grid'       => $this->grid,
            'name'       => $this->name,
            'ajax'       => $ajax,
            'sort'       => $sort,
            'sort_type'  => $sort_type,
            'filter'     => json_encode($filter),
            'filter_raw' => $filter,
            'options'    => $options,
        );

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->render($this->grid->template('index_content'), $array);
        }

        return $this->render($this->grid->template('index'), $array);
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

    public function editAction($id)
    {
        $this->initialize();
        
        $this->object = $this->getQuery()->findPk($id);
        if (!$this->object) {
            throw $this->createNotFoundException('Not found');
        }

        $request = $this->getRequest();

        $form = $this->createForm($this->getForm(), $this->object);

        $file_columns = $this->grid->getColumns()->getFileColumns();
        $default_values = $this->get('admin.upload_file.manager')->getDefaultValues($file_columns, $this->object);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->get('admin.upload_file.manager')->uploadFiles($form, $file_columns, $this->object, $default_values);
                $this->object->save();

                return $this->redirect($this->generateUrl($this->routes['index']));
            }
        }

        $render = array(
            'layout'  => $this->layout,
            'object'  => $this->object,
            'form'    => $form->createView(),
            'columns' => $this->grid->getColumns(),
            'routes'  => $this->routes,
        );

        return $this->render($this->grid->template('form.edit'), $render);
    }

    public function deleteAction($id = 5)
    {
        $this->initialize();
        $page = $this->getRequest()->query->get('page', 1);

        $this->object = $this->getQuery()->findPk($id);
        if (!$this->object) {
            throw $this->createNotFoundException('Not found');
        }

        $this->object->delete();

        return $this->redirect($this->generateUrl($this->routes['index'], array('page' => $page)));
    }

    public function newAction()
    {
        $this->initialize();
        $this->object = $this->getObject();

        $request = $this->getRequest();
        $form    = $this->createForm($this->getForm(), $this->object);

        $file_columns = $this->grid->getColumns()->getFileColumns();
        $default_values = $this->get('admin.upload_file.manager')->getDefaultValues($file_columns, $this->object);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->get('admin.upload_file.manager')->uploadFiles($form, $file_columns, $this->object, $default_values);
                $this->object->save();

                return $this->redirect($this->generateUrl($this->routes['index']));
            }
        }

        $render = array(
            'layout'  => $this->layout,
            'object'  => $this->object,
            'form'    => $form->createView(),
            'columns' => $this->grid->getColumns(),
            'routes'  => $this->routes,
        );

        return $this->render($this->grid->template('form.new'), $render);
    }

    public function enableAction()
    {
        $this->initialize();
        
        $id     = (int)$this->getRequest()->query->get('id', false);
        $status = (int)$this->getRequest()->query->get('status', false);
        $setter = $this->getRequest()->query->get('setter', false);
        
        if (!$id || ($status === false))
        {
            return new JsonResponse(array('status' => -1));
        }
        $obj = $this->getQuery()->findPk($id);
        if (!$obj)
        {
            return new JsonResponse(array('status' => -2));
        }
        
        $obj->$setter($status);
        $obj->save();
        
        if ($this->getRequest()->isXmlHttpRequest())
        {
            $this->setup();
            return $this->render($this->grid->template('row'), array(
                'item' => $obj,
                'grid' => $this->grid,
                'columns' => $this->grid->getColumns(),
                'actions' => $this->grid->getActions(),
                'options' => false,
            ));
        }
        
        return $this->redirect($this->generateUrl($this->routes['index']));
    }

    public function underscore2Camelcase($str)
    {
        $words  = explode('_', strtolower($str));
        $return = '';
        foreach ($words as $word) {
            $return .= ucfirst(trim($word));
        }

        return $return;
    }

}
