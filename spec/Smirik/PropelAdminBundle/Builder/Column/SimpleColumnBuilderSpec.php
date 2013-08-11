<?php

namespace spec\Smirik\PropelAdminBundle\Builder\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleColumnBuilderSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Builder\Column\SimpleColumnBuilder');
    }
    
    private function getOptions()
    {
        return array(
            'label'   => 'Title',
            'name'    => 'test',
            'type'    => 'string',
            'builder' => 'simple',
            'options' => array(
                'listable'   => false,
                'editable'   => true,
                'filterable' => true,
                'sortable'   => true,
            )
        );
    }
    
    function it_should_return_new_simple_column_after_create()
    {
        $this->create($this->getOptions())->shouldHaveType('Smirik\PropelAdminBundle\Column\SimpleColumn');
    }
    
}
