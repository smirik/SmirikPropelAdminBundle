<?php

namespace spec\Smirik\PropelAdminBundle\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestProcessManagerSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Manager\RequestProcessManager');
    }
    
    function it_should_validate_sort_types_and_return_desc_by_default()
    {
        $this->validateSortType('asc')->shouldBe('asc');
        $this->validateSortType('desc')->shouldBe('desc');
        $this->validateSortType('omg')->shouldBe('desc');
    }
    
    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\QueryStub $query
     */
    function it_should_sort_collection($query)
    {
        $query->orderByName(Argument::any())->willReturn($query);
        $query->filterByName(Argument::any())->willReturn($query);
        $this->sort($query, 'name', 'asc')->shouldReturnAnInstanceOf('\Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\QueryStub');
        $this->filter($query, array('name' => 'value'))->shouldReturnAnInstanceOf('\Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\QueryStub');
    }
    
}
