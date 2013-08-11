<?php

namespace Smirik\PropelAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class PropelAdminBuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('propel:admin:build')
            ->setDescription('Setup propel admin classes')
            ->addOption('controller', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
            ->addOption('model_name', false, InputOption::VALUE_REQUIRED, 'Propel model name')
            ->addOption('model', false, InputOption::VALUE_REQUIRED, 'Propel model class')
            ->addOption('query', false, InputOption::VALUE_REQUIRED, 'Propel query class')
            ->addOption('form', false, InputOption::VALUE_REQUIRED, 'Admin form class')
            ->addOption('admin_prefix', false, InputOption::VALUE_REQUIRED, 'Prefix for all URLs')
            ->addOption('url_prefix', false, InputOption::VALUE_REQUIRED, 'Local prefix for current model')
            ->addOption('layout', false, InputOption::VALUE_REQUIRED, 'Layout to extend')
            ->addOption('route', false, InputOption::VALUE_REQUIRED, 'Should the script generate routing file?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'blue', array());
        $output->getFormatter()->setStyle('greet', $style);
        
        $generator_manager = $this->getContainer()->get('admin.generator.manager');
        
        $this->greetings($output);
        
        $dialog = $this->getHelperSet()->get('dialog');
        $controller_configurator = $this->getContainer()->get('admin.console.controller.dialog')->ask($input, $output, $dialog);
        
        $create_dir = $generator_manager->createDirectories($output, $controller_configurator->getBundle());
        if (!$create_dir)
        {
            throw new \Exception('Cannot create directory '.$create_dir);
        }

        $column_dialog = $this->getContainer()->get('admin.console.column.dialog');
        $column_dialog->setPropelColumns($generator_manager->getPropelColumns($controller_configurator));
        $column_configurator = $column_dialog->ask($input, $output, $dialog);
        
        $action_configurator = $this->getContainer()->get('admin.console.action.dialog')->ask($input, $output, $dialog);
        
        $route = $input->getOption('route');
        if (!$route) {
            $route = $dialog->ask($output, '<info><info>Should the script generate routing.yml?</info></info> <comment>[yes]</comment>: ', true);
        }
        
        /** Create config & base files */
        $generator_manager->createYamlConfig($controller_configurator, $column_configurator, $action_configurator);
        
        if ($generator_manager->createController($controller_configurator)) {
            $output->writeln(array('', '<comment>Generation complete. Controller created.</comment>', ''));
        } else
        {
            $output->writeln(array('', '<comment>Generation complete. BaseController created. Do not forget to extend current controller.</comment>', ''));
        }

        if ($column_configurator->getCreateForm())
        {
            if ($generator_manager->createForm($controller_configurator, $column_configurator, $action_configurator))
            {
                $output->writeln(array('<comment>Generation complete. Form file created.</comment>', ''));
            } else
            {
                $output->writeln(array('<comment>Generation complete. Base Form file created. Do not forget to extend current form class.</comment>', ''));
            }
        }
        
        if ($route) {
            $generator_manager->createRouting($controller_configurator);
    		$output->writeln('<comment>Routing generation complete.</comment>');
        }

    }
    
    protected function greetings($output)
    {
        $output->writeln(array('', '<greet>Welcome to Propel Admin Controller generator</greet>', ''));
        $output->writeln(
            array(
                'This generator creates controller and form classes',
                'using given values. You can specify it as options',
                'or use interactive generator.',
                '',
            )
        );
    }

}