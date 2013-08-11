<?php

namespace spec\Smirik\PropelAdminBundle\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SingleActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Action\SingleAction');
    }
    
    function it_should_be_an_action()
    {
        $this->shouldBeAnInstanceOf('Smirik\PropelAdminBundle\Action\Action');
        $this->shouldImplement('Smirik\PropelAdminBundle\Action\ActionInterface');
    }
    
    function it_should_return_object_type()
    {
        $this->getType()->shouldBe('single');
    }
    
    function it_should_return_object_alias()
    {
        $this->getAlias()->shouldBe('single');
    }
    
}
