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
     * @var string $model
     * @var string $model_name
     * @return array
     */
    public function getPropelColumns($model, $model_name)
    {
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
    public function createYamlConfig($columns, $actions, $bundle, $model_name)
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump(array('columns' => $columns, 'actions' => $actions), 10);
        $base_path = $this->bundle($bundle);
        $yaml_path = $base_path.'/Resources/config/PropelAdmin/';

        if (!is_dir($yaml_path)) {
            mkdir($yaml_path, '0755', true);
        }

        $path = $yaml_path.'Admin'.$model_name.'Controller.yml';
        file_put_contents($path, $yaml);
    }

    /**
     * Creates base admin controller class & inherited (if not already created)
     * @var array $data
     * @return bool
     */
    public function createController($data)
    {
        $controller_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/BaseAdminController.php.dist');
        $content         = file_get_contents($controller_dist);

        $content = str_replace('**Controller**', $data['controller'], $content);
        $content = str_replace('**ModelName**', $data['model_name'], $content);
        $content = str_replace('**layout**', $data['layout'], $content);
        $content = str_replace('**model_prefix**', $data['model_prefix'], $content);
        $content = str_replace('**Query**', '\\'.$data['query'], $content);
        $content = str_replace('**Form**', $data['form'], $content);
        $content = str_replace('**Model**', '\\'.$data['model'], $content);
        $content = str_replace('**bundle**', $data['bundle'], $content);

        /**
         * Write generated content into controller file.
         */
        $base_path       = $this->container->get('kernel')->locateResource('@'.$data['bundle']);
        $controller_file = $base_path.'/Controller/Base/Admin'.$data['model_name'].'Controller.php';
        file_put_contents($controller_file, $content);

        if ($this->createMainController($data['controller'], $data['model_name'], $data['bundle'])) {
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
    public function createMainController($controller, $model_name, $bundle)
    {
        $controller_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/AdminController.php.dist');
        $content         = file_get_contents($controller_dist);

        $content = str_replace('**Controller**', $controller, $content);
        $content = str_replace('**ModelName**', $model_name, $content);

        /**
         * Write generated content into controller file.
         */
        $base_path       = $this->bundle($bundle);
        $controller_file = $base_path.'/Controller/Admin'.$model_name.'Controller.php';

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
    public function createForm($data, $form_text)
    {
        $form_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/BaseFormType.php.dist');
        $content   = file_get_contents($form_dist);

        $tmp           = explode("\\", $data['form']);
        $form_filename = $tmp[count($tmp) - 1];

        $content = str_replace('**Controller**', $data['controller'], $content);
        $content = str_replace('**Model**', $data['model'], $content);
        $content = str_replace('**ModelName**', $data['model_name'], $content);
        $content = str_replace('**FormName**', $form_filename, $content);
        $content = str_replace('**FIELDS**', $form_text, $content);

        $base_path = $this->container->get('kernel')->locateResource('@'.$data['bundle']);
        $form_path = $base_path.'/Form/Type/Base/';
        $form_file = $form_path.$form_filename.'.php';
        file_put_contents($form_file, $content);
        
        if ($this->createMainForm($data['controller'], $data['form'], $data['bundle']))
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
    public function createMainForm($controller, $form, $bundle)
    {
        $form_dist = $this->container->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/FormType.php.dist');
        $content   = file_get_contents($form_dist);

        $tmp           = explode("\\", $form);
        $form_filename = $tmp[count($tmp) - 1];

        $content = str_replace('**Controller**', $controller, $content);
        $content = str_replace('**FormName**', $form_filename, $content);

        $base_path = $this->bundle($bundle);
        $form_path = $base_path.'/Form/Type/';
        $form_file = $form_path.$form_filename.'.php';
        
        if (is_file($form_file)) {
            return false;
        }
        
        file_put_contents($form_file, $content);
        return true;
    }

}
