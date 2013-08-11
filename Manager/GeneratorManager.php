<?php

namespace Smirik\PropelAdminBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Yaml\Dumper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class GeneratorManager extends ContainerAware
{
    
    public function bundle($bundle)
    {
        return $this->container->get('kernel')->locateResource('@'.$bundle);
    }

    /**
     * @var string $dir
     * @var object $output
     * @var Symfony\Component\Filesystem\Filesystem $fs
     * @return bool
     */
    public function checkDirectory($dir, $output, $fs)
    {
        if (!is_dir($dir)) {
            $output->writeln(array('', '<comment>Creating directory '.$dir, ''));
            
            try {
                $fs->mkdir($dir);
            } catch (IOException $e) {
                echo "An error occurred while creating your directory";
            }
            
            if (!is_dir($dir)) {
                $output->writeln(array('', '<error>Error: cannot create '.$dir.'. Please create it manually.</error>', ''));

                return false;
            }
        }

        return true;
    }

    /**
     * @var object $output
     * @var string $bundle
     * @return bool
     */
    public function createDirectories($output, $bundle)
    {
        $base_path = $this->bundle($bundle);
        $propel_admin_dir = $base_path.'/Resources/config/PropelAdmin';
        $form_type_dir    = $base_path.'/Form/Type/Base';
        $controller_dir   = $base_path.'/Controller/Base';
        
        $fs = new Filesystem();

        if ($this->checkDirectory($propel_admin_dir, $output, $fs) && $this->checkDirectory($form_type_dir, $output, $fs) && $this->checkDirectory($controller_dir, $output, $fs)) {
            return true;
        }

        return false;
    }

    /**
     * Get list of columns of object
     * @param Smirik\PropelAdminBundle\Command\Configurator\ControllerConfigurator $configurator
     * @return array
     */
    public function getPropelColumns($configurator)
    {
        $model      = $configurator->getModel();
        $model_name = $configurator->getModelName();
        
        $tmp = explode('\\', $model);
        unset($tmp[count($tmp) - 1]);
        $table_map_class = implode('\\', $tmp).'\\map\\'.$model_name.'TableMap';
        $propel_columns = array();
        if (class_exists($table_map_class)) {
            $table_map = new $table_map_class;
            foreach ($table_map->getColumns() as $column) {
                $propel_columns[] = strtolower($column->getName());
            }
        }

        return $propel_columns;
    }

    /**
     * @var array $columns
     * @var array $actions
     * @var string $bundle
     * @var string $model_name
     * @return void
     */
    public function createYamlConfig($controller_configurator, $column_configurator, $action_configurator)
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump(array('columns' => $column_configurator->toArray(), 'actions' => $action_configurator->toArray($controller_configurator->getUrlPrefix())), 10);
        $base_path = $this->bundle($controller_configurator->getBundle());
        $yaml_path = $base_path.'/Resources/config/PropelAdmin/';

        if (!is_dir($yaml_path)) {
            mkdir($yaml_path, '0755', true);
        }

        $path = $yaml_path.'Admin'.$controller_configurator->getModelName().'Controller.yml';
        file_put_contents($path, $yaml);
    }

    /**
     * Creates base admin controller class & inherited (if not already created)
     * @var array $data
     * @return bool
     */
    public function createController($controller_configurator)
    {
        $controller_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/BaseAdminController.php.dist');
        $content         = file_get_contents($controller_dist);
        $content         = $controller_configurator->placeholder($content);
        
        $base_path       = $this->container->get('kernel')->locateResource('@'.$controller_configurator->getBundle());
        $controller_file = $base_path.'/Controller/Base/Admin'.$controller_configurator->getModelName().'Controller.php';
        file_put_contents($controller_file, $content);

        if ($this->createMainController($controller_configurator)) {
            return true;
        }

        return false;
    }

    /**
     * Creates inherited class
     * @param  string $controller
     * @param  string $model_name
     * @param  string $bundle
     * @return bool
     */
    public function createMainController($controller_configurator)
    {
        $controller_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/AdminController.php.dist');
        $content         = file_get_contents($controller_dist);
        $content         = $controller_configurator->placeholder($content);

        $base_path       = $this->bundle($controller_configurator->getBundle());
        $controller_file = $base_path.'/Controller/Admin'.$controller_configurator->getModelName().'Controller.php';

        if (is_file($controller_file)) {
            return false;
        }

        file_put_contents($controller_file, $content);

        return true;
    }

    /**
     * Form file generation
     * @var array $data
     * @var string $form_text
     * @return void
     */
    public function createForm($controller_configurator, $column_configurator, $action_configurator)
    {
        $form_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/BaseFormType.php.dist');
        $content   = file_get_contents($form_dist);
        $content   = $column_configurator->formPlaceholder($content, $controller_configurator);

        $base_path = $this->container->get('kernel')->locateResource('@'.$controller_configurator->getBundle());
        $form_path = $base_path.'/Form/Type/Base/';
        $form_file = $form_path.$controller_configurator->getFormFilename().'.php';
        file_put_contents($form_file, $content);
        
        if ($this->createMainForm($controller_configurator, $column_configurator))
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * @var string $controller
     * @var string $form
     * @var string $bundle
     * @return bool
     */
    public function createMainForm($controller_configurator, $column_configurator)
    {
        $form_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/FormType.php.dist');
        $content   = file_get_contents($form_dist);
        $content   = $column_configurator->formPlaceholder($content, $controller_configurator);

        $base_path = $this->bundle($controller_configurator->getBundle());
        $form_path = $base_path.'/Form/Type/';
        $form_file = $form_path.$controller_configurator->getFormFilename().'.php';
        
        if (is_file($form_file)) {
            return false;
        }
        
        file_put_contents($form_file, $content);
        return true;
    }
    
    public function createRouting($controller_configurator)
    {
		$routing_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/routing.yml.dist');
		$content = file_get_contents($routing_dist);
		
		$path = $this->container->get('kernel')->locateResource('@'.$controller_configurator->getBundle());
		$path = $path.'Resources/config/routing.yml';
		$fd = fopen($path, 'a+');
		
		$content = str_replace('**url_prefix**', $controller_configurator->getUrlPrefix(), $content);
		$content = str_replace('**Controller**', str_replace("\\", "",$controller_configurator->getController().':'.'Admin'.$controller_configurator->getModelName()), $content);
		
		fputs($fd, "\n".$content);
		fclose($fd);
    }

}
