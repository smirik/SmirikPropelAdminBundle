<?php

namespace Smirik\PropelAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class GenerateControllerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('propeladmin:generate:controller')
            ->setDescription('Generate propel admin controller')
            ->addOption('controller', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('model_name', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('model', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('query', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('form', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('admin_prefix', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('model_prefix', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('layout', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'blue', array());
        $output->getFormatter()->setStyle('greet', $style);
        
        $generator_manager = $this->getContainer()->get('admin.generator.manager');
        
        $output->writeln(array('', '<greet>Welcome to Propel Admin Controller generator</greet>', ''));
        $output->writeln(
            array(
                'This generator creates controller and form classes',
                'using given values. You can specify it as options',
                'or use interactive generator.',
                '',
            )
        );
        
        /**
         * Get general data about bundle, controller & entity
         */
        $data       = $this->askGeneralData($input, $output);
        $create_dir = $generator_manager->createDirectories($output, $data['bundle']);
        if (!$create_dir)
        {
            return false;
        }
        
        $propel_columns = $generator_manager->getPropelColumns($data['model'], $data['model_name']);

        $columns_data = $this->askColumns($input, $output, $propel_columns);
        $actions_data = $this->askActions($input, $output, $data['model_prefix']);

        /**
         * Create config & base files
         */
        $generator_manager->createYamlConfig($columns_data['columns'], $actions_data, $data['bundle'], $data['model_name']);
        if ($generator_manager->createController($data)) {
            $output->writeln(array('', '<comment>Generation complete. Controller created.</comment>', ''));
        } else
        {
            $output->writeln(array('', '<comment>Generation complete. BaseController created. Do not forget to extend current controller.</comment>', ''));
        }

        if ($columns_data['create_form'])
        {
            if ($generator_manager->createForm($data, $columns_data['form_text']))
            {
                $output->writeln(array('<comment>Generation complete. Form file created.</comment>', ''));
            } else
            {
                $output->writeln(array('<comment>Generation complete. Base Form file created. Do not forget to extend current form class.</comment>', ''));
            }
        }

    }

    protected function askGeneralData(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        $controller = $input->getOption('controller');
        if (!$controller) {
            $controller = $dialog->ask($output, '<question>Please specify namespace for Controller (Vendor\Bundle):</question> ', false);
        }
        $controller = $this->slashes($controller);

        $model_name = $input->getOption('model_name');
        if (!$model_name) {
            $model_name = $dialog->ask($output, '<question>Please specify Model name used in Controller name (e.g. Page, controller would be AdminPage):</question> ', false);
        }

        $model = $input->getOption('model');
        if (!$model) {
            $model = $controller.'\\Model\\'.$model_name;
            $model = $dialog->ask($output, '<question>Please enter the name of object</question> <comment>['.$model.']</comment>: ', $model);
        }
        $model = $this->slashes($model);

        $query = $input->getOption('query');
        if (!$query) {
            $query_default = $model.'Query';
            $query         = $dialog->ask($output, '<question>Please enter the name of query class</question> <comment>['.$query_default.']</comment>: ', $query_default);
        }
        $query = $this->slashes($query);

        $form = $input->getOption('form');
        if (!$form) {
            $form = $controller.'\\Form\\Type\\'.$model_name.'Type';
            $form = $dialog->ask($output, '<question>Please enter the full name of form object</question> <comment>['.$form.']</comment>: ', $form);
        }
        $form = $this->slashes($form);

        $admin_prefix = $input->getOption('admin_prefix');
        if (!$admin_prefix) {
            $admin_prefix = $dialog->ask($output, '<question>Please specify the prefix for admin without object part</question> <comment>[/admin]</comment>: ', '/admin');
        }

        $model_prefix = $input->getOption('model_prefix');
        if (!$model_prefix) {
            $model_prefix = $dialog->ask($output, '<question>Please specify model prefix (e.g. pages ), full path will be: '.$admin_prefix.'/pages :</question> ', false);
        }

        $bundle = str_replace('\\', '', $controller);
        $layout = $input->getOption('layout');
        if (!$layout) {
            $layout = str_replace('\\', '', $controller).':Admin:layout.html.twig';
            $layout = $dialog->ask($output, '<question>Please specify base layout for admin part</question> <comment>['.$layout.']</comment>: ', $layout);
        }

        return array(
            'controller'   => $controller,
            'model_name'   => $model_name,
            'model'        => $model,
            'query'        => $query,
            'form'         => $form,
            'admin_prefix' => $admin_prefix,
            'model_prefix' => $model_prefix,
            'bundle'       => $bundle,
            'layout'       => $layout,
        );
    }

    protected function askColumns(InputInterface $input, OutputInterface $output, $propel_columns)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        /**
         * Asking about columns
         */
        $output->writeln(
            array(
                'You can add different columns to your grid and create / edit form.',
                'Also you can generate form using this values.',
                'Also you could specify columns you would add into form.',
                ' ',
                '<info>Avaliable column types:</info> <comment>string, integer, text, boolean, date, collection, array</comment>',
                ' ',
                '<info>Object columns:</info> <comment>'.implode(', ', $propel_columns).'</comment>',
                ' ',
            )
        );

        $create_form = $dialog->ask($output, '<info>Generate form class based on editable columns?</info> <comment>[no]</comment>:  ', false);
        if ($create_form == 'yes') {
            $create_form = true;
        } else {
            $create_form = false;
        }

        $columns      = array();
        $form_columns = '';
        while (true) // always want to write this :D
        {
            $name = $dialog->ask($output, '<info>Specify column name (press </info><comment><return></comment><info> to stop adding columns): </info> ', false);
            if (!$name) {
                break;
            }
            $label = $dialog->ask($output, '<info>Specify column label</info> <comment>['.ucfirst($name).']</comment>: ', ucfirst($name));
            $type  = $dialog->ask($output, '<info>Specify column type</info> <comment>[string]</comment>: ', 'string');

            $options = array();

            $options['listable']   = $dialog->ask($output, '<info>Should column be listed in table?</info> <comment>[yes]</comment>: ', 'yes');
            $options['editable']   = $dialog->ask($output, '<info>Should column be listed in edit form?</info> <comment>[yes]</comment>: ', 'yes');
            $options['sortable']   = $dialog->ask($output, '<info>Should column be sortable?</info> <comment>[yes]</comment>: ', 'yes');
            $options['filterable'] = $dialog->ask($output, '<info>Should column be filterable?</info> <comment>[yes]</comment>: ', 'yes');

            if ($create_form && ($options['editable'] == 'yes')) {
                /**
                 * Form code should be generated in *Column class
                 */
                $form_columns[] = '      ->add(\''.$name.'\')';
            }

            foreach ($options as $key => $value) {
                if ($value == 'yes') {
                    $options[$key] = true;
                } else {
                    $options[$key] = false;
                }
            }

            $columns[$name] = array(
                'label' => $label,
                'name'  => $name,
                'type'  => $type,
                'builder' => 'simple',
                'options' => $options,
            );
            $output->writeln("\n".'Column was added.'."\n");
        }

        if (count($form_columns) > 0) {
            $form_text = implode("\n      ", $form_columns);
        }

        return array(
            'columns'     => $columns,
            'form_text'   => $form_text,
            'create_form' => $create_form,
        );

    }

    protected function askActions($input, $output, $model_prefix)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        /**
         * Ask about actions
         */
        $new_action    = $dialog->ask($output, '<info>Activate new action?</info> <comment>[yes]</comment>: ', 'yes');
        $edit_action   = $dialog->ask($output, '<info>Activate edit action?</info> <comment>[yes]</comment>: ', 'yes');
        $delete_action = $dialog->ask($output, '<info>Activate delete action?</info> <comment>[yes]</comment>: ', 'yes');

        $actions = array();
        if ($new_action == 'yes') {
            $actions['create'] = array(
                'route'    => 'admin_'.$model_prefix.'_new',
                'extends' => 'create',
            );
        }

        if ($edit_action == 'yes') {
            $actions['edit'] = array(
                'route'    => 'admin_'.$model_prefix.'_edit',
                'extends' => 'edit',
            );
        }

        if ($delete_action == 'yes') {
            $actions['delete'] = array(
                'route'    => 'admin_'.$model_prefix.'_delete',
                'extends' => 'delete',
            );
        }

        return $actions;
    }

    private function slashes($var)
    {
        return str_replace('/', '\\', $var);
    }

}