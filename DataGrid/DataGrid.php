<?php

namespace Smirik\PropelAdminBundle\DataGrid;

use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ActionCollection;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\ColumnCollection;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class DataGrid implements \IteratorAggregate
{

    /**
     * @var Smirik\PropelAdminBundle\DataGrid\ConfigLoader $config_loader
     */
    protected $config_loader;

    /**
     * @var Smirik\PropelAdminBundle\DataGrid\TemplateResolver $template_resolver
     */
    protected $template_resolver;

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

    /**
     * All native actions(i.e. edit, delete etc) are stored and displayed separately
     * @var ActionCollection
     */
    protected $nativeActions;

    protected $container;

    /**
     * Show options
     */
    protected $has_avalanche = false;

    protected $page  = 1;
    protected $limit = 15;

    public function __construct($container)
    {
        $this->container = $container;
        
        $this->actions = new ActionCollection();
        $this->columns = new ColumnCollection();
        $this->nativeActions = new ActionCollection();
    }

    public function setupAvalanche()
    {
        $this->has_avalanche = true;
    }

    public function hasAvalanche()
    {
        return $this->has_avalanche;
    }

    public function getControllerConfig($bundle, $class)
    {
        $path   = $this->container->get('kernel')->locateResource('@'.$bundle.'/Resources/config/PropelAdmin/'.$class.'.yml');
        $yaml = new Parser();
        try {
            $config = $yaml->parse(file_get_contents($path));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return $config;
    }
    
    /**
     * Loads & setup actions & columns config.
     * @param string $bundle
     * @param string $class
     * @return void
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function load($bundle, $class)
    {
        $config = $this->getControllerConfig($bundle, $class);
        $data = $this->config_loader->load($config);

        $this->columns       = $data['columns'];
        $this->actions       = $data['actions'];
        $this->nativeActions = $data['native_actions'];

        $this->template_resolver->resolve($config);
    }

    /**
     * Get template related to relative name
     * @param  string     $name
     * @throws \Exception
     * @return string
     */
    public function template($name)
    {
        return $this->template_resolver->find($name);
    }

    /**
     * Getters & Setters
     */
    public function setConfigLoader($config_loader)
    {
        $this->config_loader = $config_loader;
    }

    public function setTemplateResolver($template_resolver)
    {
        $this->template_resolver = $template_resolver;
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

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return ActionCollection
     */
    public function getNativeActions()
    {
        return $this->nativeActions;
    }

}
