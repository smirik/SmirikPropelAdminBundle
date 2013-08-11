<?php

namespace spec\Smirik\PropelAdminBundle\Builder\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChainActionBuilderSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Builder\Action\ChainActionBuilder');
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

    
    function it_should_return_new_object_action_after_create()
    {
        $this->create($this->getOptions())->shouldHaveType('Smirik\PropelAdminBundle\Action\ChainAction');
    }
    
}
