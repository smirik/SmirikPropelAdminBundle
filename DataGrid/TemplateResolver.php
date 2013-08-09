<?php

namespace Smirik\PropelAdminBundle\DataGrid;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class TemplateResolver
{

    protected $container;

    /**
     * Propel admin templates
     * @var array $templates
     */
    protected $templates = array();

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

    /**
     * Find template by name
     * @param  string $name
     * @return string
     */
    public function find($name)
    {
        /**
         * @todo refactoring
         */
        $tmp = explode('.', $name);
        $num = count($tmp);
        if ($num == 1) {
            return $this->templates[$name];
        } elseif ($num == 2) {
            if (!isset($this->templates[$tmp[0]][$tmp[1]])) {
                throw new \Exception('Template not found');
            }

            return $this->templates[$tmp[0]][$tmp[1]];
        }
    }

}
