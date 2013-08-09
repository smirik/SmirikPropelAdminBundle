<?php

namespace spec\Smirik\PropelAdminBundle\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChainActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Action\ChainAction');
    }
    
    function it_should_be_an_action()
    {
        $this->shouldBeAnInstanceOf('Smirik\PropelAdminBundle\Action\ObjectAction');
        $this->shouldImplement('Smirik\PropelAdminBundle\Action\ActionInterface');
    }
    
    function it_should_return_object_type()
    {
        $this->getType()->shouldBe('object');
    }
    
    function it_should_return_object_alias()
    {
        $this->getAlias()->shouldBe('chain');
    }


    function it_should_have_correct_setup_for_getter_and_setter()
    {
        $this->setup($this->getOptions());
        $this->getGetter()->shouldBe('getTest');
        $this->getSetter()->shouldBe('setTest');
    }

    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\ItemStub $item_stub
     */
    function is_should_return_correct_next_value($item_stub)
    {
        $this->setup($this->getOptions());
        
        $item_stub->getTest()->willReturn(1);
        $this->getNextValue($item_stub)->shouldBe(0);
        $item_stub->getTest()->willReturn(0);
        $this->getNextValue($item_stub)->shouldBe(-1);
        $item_stub->getTest()->willReturn(-1);
        $this->getNextValue($item_stub)->shouldBe(1);
        
    }

    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\ItemStub $item_stub
     */
    function it_should_return_correct_button_text($item_stub)
    {
        $this->setup($this->getOptions());
        
        $item_stub->getTest()->willReturn(0);
        $this->getView($item_stub)->shouldBe('Unapproved');
        $item_stub->getTest()->willReturn(1);
        $this->getView($item_stub)->shouldBe('Approved');
        $item_stub->getTest()->willReturn(-1);
        $this->getView($item_stub)->shouldBe('Rejected');
    }
    
    private function getOptions()
    {
        return array(
            'label'   => 'Update',
            'name'    => 'edit',
            'builder' => 'publish',
            'route'   => 'admin_edit',
            'options' => array(
                'route_with_id' => true,
                'confirmation'  => false,
                'template'      => 'SmirikPropelAdminBundle:Admin/Action:publish.html.twig',
                'filter'        => 'SmirikPropelAdminBundle:Admin/Action/Filter:publish.html.twig',
            ),
            'getter' => 'getTest',
            'setter' => 'setTest',
            'data' => array(
                array('key' => 1, 'text' => 'Approved'),
                array('key' => 0, 'text' => 'Unapproved'),
                array('key' => -1, 'text' => 'Rejected'),
            )
        );
    }
    
}
