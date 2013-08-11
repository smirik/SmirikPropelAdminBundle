<?php

namespace spec\Smirik\PropelAdminBundle\Builder\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileColumnBuilderSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Builder\Column\FileColumnBuilder');
    }
    
    private function getOptions()
    {
        return array(
            'label'   => 'Title',
            'name'    => 'test',
            'type'    => 'string',
            'builder' => 'simple',
            'options' => array(
                'listable'    => false,
                'editable'    => true,
                'filterable'  => true,
                'sortable'    => true,
                'upload_path' => '/uploads/files/',
            )
        );
    }
    
    function it_should_return_new_file_column_after_create()
    {
        $this->create($this->getOptions())->shouldHaveType('Smirik\PropelAdminBundle\Column\FileColumn');
    }
    
    
}
