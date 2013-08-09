<?php
    
namespace Smirik\PropelAdminBundle\Manager;

class RequestProcessManager
{
 
    /**
     * Sort type could be only asc & desc. If OK, returns $sort_type, else returns desc.
     * @param string $sort_type
     * @return string
     */
    public function validateSortType($sort_type)
    {
        $sort_type = strtolower($sort_type);
        if (in_array($sort_type, array('asc', 'desc')))
        {
            return $sort_type;
        }
        return 'desc';
    }
    
    /**
     * Apply sort options to query
     * @param object $collection_query 
     * @param string $sort
     * @param string $sort_type
     * @return object
     */
    public function sort($collection_query, $sort, $sort_type)
    {
        $sort_type = $this->validateSortType($sort_type);
        
        $method = 'orderById';
        if ($sort) {
            $method = (string)'orderBy'.\Symfony\Component\DependencyInjection\Container::camelize($sort);
        }
        
        $collection_query = $collection_query
            ->_if($method)
                ->$method($sort_type)
            ->_endIf()
        ;
        return $collection_query;
    }
    
    public function filter($collection_query, $filter)
    {
        if ($filter && is_array($filter)) {
            foreach ($filter as $key => $value) {
                if ($value === '') {
                    continue;
                }
                $filter_method = (string)'filterBy'.\Symfony\Component\DependencyInjection\Container::camelize($key);
                $int_value     = (int)$value;
                if ((string)$int_value != $value) {
                    $value = '%'.$value.'%';
                }
                $collection_query
                    ->$filter_method($value);
            }
        }
        return $collection_query;
    }
    
    
}