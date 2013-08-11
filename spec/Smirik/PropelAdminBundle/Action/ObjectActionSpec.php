<?php

namespace spec\Smirik\PropelAdminBundle\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Action\ObjectAction');
    }
    
    function it_should_be_an_action()
    {
        $this->shouldBeAnInstanceOf('Smirik\PropelAdminBundle\Action\Action');
        $this->shouldImplement('Smirik\PropelAdminBundle\Action\ActionInterface');
    }
    
    function it_should_return_object_type()
    {
        $this->getType()->shouldBe('object');
    }
    
    function it_should_return_object_alias()
    {
        $this->getType()->shouldBe('object');
    }
    
    function it_should_return_correct_default_template()
    {
        $this->getTemplate()->shouldBe('SmirikPropelAdminBundle:Admin/Action:object.html.twig');
    }
    
    function it_should_test_standard_abstract_action_class()
    {
        $options = array(
            'label'   => 'Update',
            'name'    => 'edit',
            'builder' => 'object',
            'route'   => 'admin_edit',
            'options' => array(
                'route_with_id' => true,
                'confirmation' => false,
                'filter' => 'SmirikPropelAdminBundle:Admin/Action/Filter:chain.html.twig',
            )
        );
        
        $this->shouldThrow('\Smirik\PropelAdminBundle\Action\ActionRequiredConfigException')->duringSetup(array());

        $this->setup($options)->shouldReturn(null);
        $this->getLabel()->shouldBe('Update');
        $this->getName()->shouldBe('edit');
        $this->getRoute()->shouldBe('admin_edit');
        $this->withConfirmation()->shouldBe(false);
        $this->shouldHaveFilter();
        $this->getFilter()->shouldBe('SmirikPropelAdminBundle:Admin/Action/Filter:chain.html.twig');
        $this->shouldBeNative();
        
        $this->setTemplate('new template');
        $this->getTemplate()->shouldBe('new template');
    }
        
    public function getMatchers()
    {
        return [
            'haveValue' => function($array, $value) {
                return in_array($value, $array);
            },
            'haveValues' => function($array, $values) {
                foreach ($values as $value)
                {
                    if (!in_array($value, $array)) {
                        return false;
                    }
                }
                return true;
            },
        ];
    }
    
    
}
