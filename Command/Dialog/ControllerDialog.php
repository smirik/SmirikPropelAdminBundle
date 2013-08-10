<?php
    
namespace Smirik\PropelAdminBundle\Command\Dialog;

class ControllerDialog extends ConfigurableDialog
{
    
    public function setup()
    {
        $this->setNamespace();
        $this->setModelName();
        $this->setModel();
        $this->setQuery();
        $this->setForm();
        $this->setAdminPrefix();
        $this->setUrlPrefix();
        $this->setLayout();
    }
    
    public function setNamespace()
    {
        $controller = $this->input->getOption('controller');
        if (!$controller) {
            $controller = $this->dialog->askAndValidate($this->output, '<question>Please specify namespace for Controller (Vendor\Bundle):</question> ', function($answer){
                if ('Bundle' !== substr($answer, -6)) {
                    throw new \RunTimeException(
                        'The name of the bundle should be suffixed with \'Bundle\''
                    );
                }
                if (strpos($answer, "\\") === false) {
                    throw new \RunTimeException(
                        'The name of Bundle should have at least 1 \\ symbol'
                    );
                }
                return $answer;
            }, false);
        }
        $controller = $this->slashes($controller);
        $this->configurator->setController($controller);
    }
    
    public function setModelName()
    {
        $model_name = $this->input->getOption('model_name');
        if (!$model_name) {
            $model_name = $this->dialog->ask($this->output, '<question>Please specify Model name used in Controller name (e.g. Page, controller would be AdminPage):</question> ', false);
        }
        $this->configurator->setModelName($model_name);
    }
    
    public function setModel()
    {
        $model = $this->input->getOption('model');
        if (!$model) {
            $model = $this->configurator->getController().'\\Model\\'.$this->configurator->getModelName();
            $model = $this->dialog->ask($this->output, '<question>Please enter the name of object</question> <comment>['.$model.']</comment>: ', $model);
        }
        $model = $this->slashes($model);
        $this->configurator->setModel($model);
    }
    
    public function setQuery()
    {
        $query = $this->input->getOption('query');
        if (!$query) {
            $query_default = $this->configurator->getModel().'Query';
            $query         = $this->dialog->ask($this->output, '<question>Please enter the name of query class</question> <comment>['.$query_default.']</comment>: ', $query_default);
        }
        $query = $this->slashes($query);
        $this->configurator->setQuery($query);
    }
    
    public function setForm()
    {
        $form = $this->input->getOption('form');
        if (!$form) {
            $form = $this->configurator->getController().'\\Form\\Type\\'.$this->configurator->getModelName().'Type';
            $form = $this->dialog->ask($this->output, '<question>Please enter the full name of form object</question> <comment>['.$form.']</comment>: ', $form);
        }
        $form = $this->slashes($form);
        $this->configurator->setForm($form);
    }
    
    public function setAdminPrefix()
    {
        $admin_prefix = $this->input->getOption('admin_prefix');
        if (!$admin_prefix) {
            $admin_prefix = $this->dialog->ask($this->output, '<question>Please specify the prefix for admin without object part</question> <comment>[/admin]</comment>: ', '/admin');
        }
        return $admin_prefix;
    }
    
    public function setUrlPrefix()
    {
        $url_prefix = $this->input->getOption('url_prefix');
        if (!$url_prefix) {
            $url_prefix = $this->dialog->ask($this->output, '<question>Please specify url prefix (e.g. pages ), full path will be: '.$this->configurator->getAdminPrefix().'/pages :</question> ', false);
        }
        $this->configurator->setUrlPrefix($url_prefix);
    }
    
    public function setLayout()
    {
        $bundle = str_replace('\\', '', $this->configurator->getController());
        $this->configurator->setBundle($bundle);
        $layout = $this->input->getOption('layout');
        if (!$layout) {
            $layout = str_replace('\\', '', $this->configurator->getController()).':Admin:layout.html.twig';
            $layout = $this->dialog->ask($this->output, '<question>Please specify base layout for admin part</question> <comment>['.$layout.']</comment>: ', $layout);
        }
        $this->configurator->setLayout($layout);
    }
    
    private function slashes($var)
    {
        return str_replace('/', '\\', $var);
    }
    
}