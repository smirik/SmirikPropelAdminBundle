<?php

namespace Smirik\PropelAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Component\Finder\Finder;

class GenerateRoutingCommand extends ContainerAwareCommand
{
	protected function configure()
	{
    $this
			->setName('propeladmin:generate:routing')
			->setDescription('Generate propel admin routing and add it to the end of routing.yml file (or create them)')
      ->addOption('controller', false, InputOption::VALUE_REQUIRED, 'Admin controller name')
      ->addOption('model_prefix', false, InputOption::VALUE_REQUIRED, 'Model prefix in routing (without admin part)')
      ->addOption('bundle', false, InputOption::VALUE_REQUIRED, 'Bundle name')
    ;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('<info>Generating routing.file.</info>');
		
		/**
		 * Get options or ask user to enter them.
		 * @todo add validation
		 */
		$dialog = $this->getHelperSet()->get('dialog');

		$controller = $input->getOption('controller');
		if (!$controller)
		{
			$controller  = $dialog->ask($output, '<question>Please specify namespace for Controller (AcmeDemoBundle:AdminPage):</question> ', false);
		}

		$bundle = $input->getOption('bundle');
		if (!$bundle)
		{
			$tmp = explode(':', $controller);
			$bundle = $tmp[0];
			$bundle = $dialog->ask($output, '<question>Please specify bundle name ['.$bundle.']:</question> ', $bundle);
		}

		$model_prefix = $input->getOption('model_prefix');
		if (!$model_prefix)
		{
			$model_prefix  = $dialog->ask($output, '<question>Please specify model prefix (e.g. pages):</question> ', false);
		}
		
		/**
		 * Get skeleton file and replace dynamic parts.
		 */
		/**
		 * @todo do dynamic
		 */
		$routing_dist = $this->getContainer()->get('kernel')->locateResource('@SmirikPropelAdminBundle/Skeleton/routing.yml.dist');
		$content = file_get_contents($routing_dist);
		
		$path = $this->getContainer()->get('kernel')->locateResource('@'.$bundle);
		$path = $path.'Resources/config/routing.yml';
		$fd = fopen($path, 'a+');
		
		$content = str_replace('**model_prefix**', $model_prefix, $content);
		$content = str_replace('**Controller**', $controller, $content);
		
		fputs($fd, "\n".$content);
		
		fclose($fd);

		$output->writeln('<comment>Routing generation complete. Add information in '.$path.'.</comment>');

	}

}