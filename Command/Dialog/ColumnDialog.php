<?php

namespace Smirik\PropelAdminBundle\Command\Dialog;

use Smirik\PropelAdminBundle\Column\ConsoleColumn;

class ColumnDialog extends ConfigurableDialog
{

    /**
     * Array of propel columns related to the model
     * @var array $propel_columns
     */
    protected $propel_columns;

    /**
     * Available column's types
     * @var array
     */
    protected $available_types = array('string', 'integer', 'text', 'boolean', 'date', 'collection', 'array', 'file', 'image');

    public function setup()
    {
        $this->greetings();
        $this->setCreateForm();
        $this->askColumns();
    }

    public function greetings()
    {
        $this->output->writeln(
            array(
                'You can add different columns to your grid and create / edit form.',
                'Also you can generate form using this values.',
                'Also you could specify columns you would add into form.',
                ' ',
                '<info>Available column types:</info> <comment>'.implode(',', $this->available_types).'</comment>',
                ' ',
                '<info>Object columns:</info> <comment>'.implode(', ', $this->propel_columns).'</comment>',
                ' ',
            )
        );
    }

    public function setCreateForm()
    {
        $create_form = $this->dialog->ask($this->output, '<info>Generate form class based on editable columns?</info> <comment>[no]</comment>:  ', false);
        if ($create_form == 'yes') {
            $create_form = true;
        } else {
            $create_form = false;
        }
        $this->configurator->setCreateForm($create_form);
    }

    public function askColumns()
    {
        while (true) { /** always want to write this :D */
            $column = $this->askOneColumn();
            if (!$column) {
                break;
            }
        }

        return $this->configurator;
    }

    public function askOneColumn()
    {
        $column = new ConsoleColumn();
        $name = $this->dialog->ask($this->output, '<info>Specify column name (press </info><comment><return></comment><info> to stop adding columns): </info> ', false, $this->propel_columns);
        if (!$name) {
            return false;
        }
        $column->setName($name);
        $label = $this->dialog->ask($this->output, '<info>Specify column label</info> <comment>['.\Symfony\Component\DependencyInjection\Container::camelize($name).']</comment>: ', \Symfony\Component\DependencyInjection\Container::camelize($name));
        $column->setLabel($label);
        $type  = $this->dialog->ask($this->output, '<info>Specify column type</info> <comment>[string]</comment>: ', 'string', $this->available_types);
        $column->setType($type);

        $options = array();
        $options['listable']   = $this->dialog->ask($this->output, '<info>Should column be listed in table?</info> <comment>[yes]</comment>: ', 'yes');
        $options['editable']   = $this->dialog->ask($this->output, '<info>Should column be listed in edit form?</info> <comment>[yes]</comment>: ', 'yes');
        $options['sortable']   = $this->dialog->ask($this->output, '<info>Should column be sortable?</info> <comment>[yes]</comment>: ', 'yes');
        $options['filterable'] = $this->dialog->ask($this->output, '<info>Should column be filterable?</info> <comment>[yes]</comment>: ', 'yes');

        $column->setOptions($this->toBoolean($options));
        $this->configurator->addColumn($column);
        
        $this->output->writeln("\n".'Column was added.'."\n");

        return $column;
    }

    public function toBoolean($options)
    {
        foreach ($options as $key => $value) {
            if ($value == 'yes') {
                $options[$key] = true;
            } else {
                $options[$key] = false;
            }
        }

        return $options;
    }

    public function setPropelColumns($propel_columns)
    {
        $this->propel_columns = $propel_columns;
    }

}
