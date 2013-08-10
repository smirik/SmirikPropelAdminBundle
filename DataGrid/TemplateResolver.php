<?php

namespace Smirik\PropelAdminBundle\DataGrid;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class TemplateResolver
{

    protected $container;

    /**
     * Propel admin templates
     * @todo Generally, it should be protected, but then need in addTemplate, update methods with custom logic.
     * @var array $templates
     */
    public $templates = array();

    public function __construct($container)
    {
        $this->container = $container;
        $this->loadDefaultTemplates();
    }

    /**
     * Load default templates from @SmirikPropelAdminBundle/Resources/config/config.yml
     * @param none
     * @return void
     * @throws Symfony\Component\Yaml\Exception\ParseException
     */
    public function loadDefaultTemplates()
    {
        $path = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Resources/config/config.yml');
        $yaml = new Parser();
        try {
            $data = $yaml->parse(file_get_contents($path));
            $this->templates = $data['smirik_propel_admin']['templates'];
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }

    /**
     * Merge custom config templates
     * @param  array $config
     * @return void
     */
    public function resolve($config)
    {
        $templates = array();
        if (isset($config['templates'])) {
            foreach ($templates as $key => $array) {
                if (isset($config['templates'][$key])) {
                    (is_array($array)) ? $templates[$key] = $config['templates'][$key] + $this->templates[$key] : $templates[$key] = $config['templates'][$key];
                }
            }
            $this->templates = $templates;
        }
    }

}
