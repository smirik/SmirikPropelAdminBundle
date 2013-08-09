<?php

namespace spec\Smirik\PropelAdminBundle\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionColumnSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Column\CollectionColumn');
    }
    
    function it_should_have_collection_alias()
    {
        $this->getAlias()->shouldBe('collection');
    }
    
}
