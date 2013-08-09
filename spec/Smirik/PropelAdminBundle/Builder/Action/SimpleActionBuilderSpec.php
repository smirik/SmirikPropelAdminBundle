<?php

namespace spec\Smirik\PropelAdminBundle\Builder\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleActionBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Builder\Action\SimpleActionBuilder');
    }
    
    private function getOptions()
    {
        return array(
            'label'   => 'Simple',
            'name'    => 'simple_action',
            'builder' => 'simple',
            'route'   => 'admin_edit',
            'options' => array(
                'route_with_id' => true,
                'confirmation'  => false,
            )
        );
    }
    
    function it_should_return_new_simple_action_after_create()
    {
        $this->create($this->getOptions())->shouldHaveType('Smirik\PropelAdminBundle\Action\SimpleAction');
    }
    
}
