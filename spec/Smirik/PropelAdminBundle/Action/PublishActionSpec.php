<?php

namespace spec\Smirik\PropelAdminBundle\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PublishActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Action\PublishAction');
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
        $this->getAlias()->shouldBe('publish');
    }

    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\ItemStub $item_stub
     */
    function it_should_return_correct_button_text($item_stub)
    {
        $options = array(
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
            'data' => array(
                array('key' => 0, 'text' => 'Status0'),
                array('key' => 1, 'text' => 'Status1'),
            )
        );
        $this->setup($options);
        
        $item_stub->isPublished()->willReturn(true);
        $this->getView($item_stub)->shouldBe('Status1');
        
        $item_stub->isPublished()->willReturn(false);
        $this->getView($item_stub)->shouldBe('Status0');
    }
    
}
