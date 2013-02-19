<?php

namespace Smirik\PropelAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class GenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('propeladmin:generate')
            ->setDescription('Generate propel admin files: AdminController, routing. Also add routing link to app/config/routing.yml')//           ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        $controller = $dialog->ask($output, '<question>Please specify namespace for Controller (\Vendor\Bundle\Controller):</question> ', false);
        $this->generateController($input, $output);
        $output->writeln($controller);

    }

    protected function generateController($input, $output)
    {
        $command = $this->getApplication()->find('propeladmin:generate:controller');

        $arguments = array(
            'command' => 'propeladmin:generate:controller',
        );

        $input      = new ArrayInput($arguments);
        $returnCode = $command->run($input, $output);
    }

}