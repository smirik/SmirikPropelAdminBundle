<?php

namespace spec\Smirik\PropelAdminBundle\Builder\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectActionBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Builder\Action\ObjectActionBuilder');
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
    
    function it_should_return_new_object_action_after_create()
    {
        $this->create($this->getOptions())->shouldHaveType('Smirik\PropelAdminBundle\Action\ObjectAction');
    }
    
    function it_should_throw_an_exception_if_config_dont_have_name()
    {
        $options = $this->getOptions();
        unset($options['name']);
        $this->create($this->getOptions())->shouldThrow('Smirik\PropelAdminBundle\Action\ActionRequiredConfigException');
    }

    function it_should_throw_an_exception_if_config_dont_have_label()
    {
        $options = $this->getOptions();
        unset($options['label']);
        $this->create($this->getOptions())->shouldThrow('Smirik\PropelAdminBundle\Action\ActionRequiredConfigException');
    }
    
    function it_should_throw_an_exception_if_config_dont_have_builder()
    {
        $options = $this->getOptions();
        unset($options['builder']);
        $this->create($this->getOptions())->shouldThrow('Smirik\PropelAdminBundle\Action\ActionRequiredConfigException');
    }
    
    function it_should_throw_an_exception_if_config_dont_have_route()
    {
        $options = $this->getOptions();
        unset($options['route']);
        $this->create($this->getOptions())->shouldThrow('Smirik\PropelAdminBundle\Action\ActionRequiredConfigException');
    }
    
    
}
