<?php
    
namespace Smirik\PropelAdminBundle\Command\Dialog;

class ActionDialog extends ConfigurableDialog
{

    public function setup()
    {
        $new    = $this->dialog->ask($this->output, '<info>Activate new action?</info> <comment>[yes]</comment>: ', 'yes');
        $edit   = $this->dialog->ask($this->output, '<info>Activate edit action?</info> <comment>[yes]</comment>: ', 'yes');
        $delete = $this->dialog->ask($this->output, '<info>Activate delete action?</info> <comment>[yes]</comment>: ', 'yes');
        
        $this->configurator->setNew($new);
        $this->configurator->setEdit($edit);
        $this->configurator->setDelete($delete);
        
        return $this->configurator;
    }
    
}