<?php

namespace spec\Smirik\PropelAdminBundle\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileColumnSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Column\FileColumn');
    }
    
    private function getOptions()
    {
        return array(
            'label'   => 'Title',
            'name'    => 'test',
            'type'    => 'file',
            'builder' => 'simple',
            'options' => array(
                'listable'       => false,
                'editable'       => true,
                'filterable'     => true,
                'sortable'       => true,
                'sortable'       => true,
                'upload_path'    => '/uploads/documents/',
            )
        );
    }
    
    function let()
    {
        $this->setup($this->getOptions());
    }
    
    function it_should_have_file_alias()
    {
        $this->getAlias()->shouldBe('file');
    }
    
    function it_should_throw_exception_if_upload_path_is_not_specified()
    {
        $options = $this->getOptions();
        unset($options['options']['upload_path']);
        $this->shouldThrow('\Smirik\PropelAdminBundle\Column\ColumnRequiredConfigException')->duringSetup($options);
    }
    
    function it_should_be_able_to_randomize_name()
    {
        $this->randomizeName()->shouldBe(false);
        $options = $this->getOptions();
        $options['options']['randomize_name'] = true;
        
        $this->setup($options);
        $this->randomizeName()->shouldBe(true);
        
    }
    
    function it_should_guess_extension_correct()
    {
        $this->guessExtension('image1.png')->shouldBe('png');
        $this->guessExtension('image1.jpg')->shouldBe('jpg');
        $this->guessExtension('image1.pdf')->shouldBe('pdf');
        $this->guessExtension('image1.doc')->shouldBe('doc');
        $this->guessExtension('image1.omg')->shouldBe(false);
    }
    
    /**
     * @param \Smirik\PropelAdminBundle\spec\Smirik\PropelAdminBundle\Stub\ItemStub $item_stub
     */
    function it_should_return_correct_icon_name($item_stub)
    {
        $item_stub->getTest()->willReturn('image1.jpg');
        $this->getView($item_stub)->shouldBe('jpg');
        
        $item_stub->getTest()->willReturn('image1.pdf');
        $this->getView($item_stub)->shouldBe('pdf');
        
        $item_stub->getTest()->willReturn('image1.omg');
        $this->getView($item_stub)->shouldBe('file');
    }
    
}
