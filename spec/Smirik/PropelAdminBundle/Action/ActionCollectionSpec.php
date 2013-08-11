<?php

namespace spec\Smirik\PropelAdminBundle\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Action\ActionCollection');
    }
    
    /**
     * @param \Smirik\PropelAdminBundle\Action\SimpleAction $action1
     * @param \Smirik\PropelAdminBundle\Action\ObjectAction $action2
     */
    function let($action1, $action2)
    {
        $action1->getAlias()->willReturn('simple');
        $action2->getAlias()->willReturn('object');

        $action1->getType()->willReturn('object');
        $action2->getType()->willReturn('object');
        
        $this->setActions(array('Action1' => $action1, 'Action2' => $action2));
    }
    
    function it_should_have_two_actions()
    {
        $this->has('Action1')->shouldBe(true);
        $this->has('Action2')->shouldBe(true);
        $this->has('Action3')->shouldBe(false);
    }
    
    function it_should_have_only_one_simple_action()
    {
        $this->getByAlias('object')->shouldHaveCount(1);
        $this->getByAlias('simple')->shouldHaveCount(1);
    }
    
    function it_should_have_two_object_actions()
    {
        $this->getByType('object')->shouldHaveCount(2);
    }
    
}
