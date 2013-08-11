<?php
    
namespace Smirik\PropelAdminBundle\Command\Dialog;

abstract class ConfigurableDialog
{
    
    /**
     * @var Symfony\Component\Console\Input\InputInterface $input
     */
    protected $input;
    /**
     * @var Symfony\Component\Console\Output\OutputInterface $output
     */
    protected $output;
    
    /**
     * @var object
     */
    protected $dialog;
    
    /**
     * @var object
     */
    protected $configurator;
    
    /**
     * Setup data related to the console command
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @param object $dialog
     * @return object
     */
    public function ask($input, $output, $dialog)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->dialog = $dialog;
        
        $this->setup();
        
        return $this->configurator;
    }
    
    /**
     * @param void
     * @return void
     */
    abstract public function setup();
    
    /**
     * @param object $configurator
     * @return void
     */
    public function setConfigurator($configurator)
    {
        $this->configurator = $configurator;
    }
    
}