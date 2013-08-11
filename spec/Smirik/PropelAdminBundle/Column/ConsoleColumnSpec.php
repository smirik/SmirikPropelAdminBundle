<?php

namespace spec\Smirik\PropelAdminBundle\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConsoleColumnSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Column\ConsoleColumn');
    }
}
