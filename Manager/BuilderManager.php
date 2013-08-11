<?php

namespace Smirik\PropelAdminBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

abstract class BuilderManager extends ContainerAware
{

    /**
     * @var array
     */
    protected $builders = array();

    /**
     * Get list of standard templates
     * @return \Symfony\Component\Finder\Finder
     */
    abstract protected function getStandard();

    /**
     * Process array of options & add default values.
     * @var array $options
     * @return array
     */
    public function useDefaults($options)
    {
        $finder = $this->getStandard();
        if (isset($options['extends']))
        {
            foreach ($finder as $file) {
                if ($file->getBasename('.yml') == $options['extends'])
                {
                    $yaml = new Parser();
                    try {
                        $value = $yaml->parse(file_get_contents($file->getRealPath()));
                    } catch (ParseException $e) {
                        printf("Unable to parse the YAML string: %s", $e->getMessage());
                    }
                    $options = array_merge($value, $options);
                }
            }
        }
        return $options;
    }
    
    /**
     * Create new entity based on alias & options
     * @param string $alias
     * @param array $options
     * @return object|bool
     */
    public function create($options)
    {
        $options = $this->useDefaults($options);
        $builder = $options['builder'];

        $action = $this->builders[$builder]->create($options);
        $action->setup($options);

        return $action;
    }

    /**
     * @return array
     */
    public function getBuilders()
    {
        return $this->builders;
    }

}