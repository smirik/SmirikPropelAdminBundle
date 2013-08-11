<?php
    
namespace Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub;

class QueryStub
{
    
    public function orderById()
    {
        return 1;
    }
    
    public function orderByName()
    {
        return 1;
    }

    public function filterByName()
    {
        return 1;
    }
    
    public function _if($arg)
    {
        return $this;
    }
    
    public function _endIf()
    {
        return $this;
    }
    
}