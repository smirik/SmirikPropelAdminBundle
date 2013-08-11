<?php

namespace spec\Smirik\PropelAdminBundle\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleColumnSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Column\SimpleColumn');
    }
    
    function it_should_be_an_action()
    {
        $this->shouldBeAnInstanceOf('Smirik\PropelAdminBundle\Column\Column');
        $this->shouldImplement('Smirik\PropelAdminBundle\Column\ColumnInterface');
    }
    
    function it_should_have_simple_alias()
    {
        $this->getAlias()->shouldBe('simple');
    }

    function it_should_throw_exception_if_not_all_configs_are_specified()
    {
        $options = $this->getOptions();
        unset($options['name']);
        $this->shouldThrow('\Smirik\PropelAdminBundle\Column\ColumnRequiredConfigException')->duringSetup($options);
    }
    
    /**
     * abstract Column testing
     */
    
    function let()
    {
        $this->setup($this->getOptions());
    }
    
    function it_should_have_correct_options_by_setup()
    {
        $this->setup($this->getOptions());
        
        $this->shouldNotBeListable();
        $this->shouldBeEditable();
        $this->shouldBeFilterable();
        $this->shouldBeSortable();
    }

    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\ItemStub $item_stub
     */
    function it_should_have_correct_value($item_stub)
    {
        $item_stub->getTest()->willReturn('OK');
        $this->getGetter()->shouldBe('Test');
        $this->getValue($item_stub)->shouldBe('OK');
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
    
}
