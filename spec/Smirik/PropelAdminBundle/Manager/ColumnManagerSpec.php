<?php

namespace spec\Smirik\PropelAdminBundle\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Manager\ColumnManager');
    }
    
    /**
     * Test BuilderManager
     */
    private function getOptions()
    {
        return array(
            'extends' => 'name',
        );
    }
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container_stub
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel_stub
     */
    function let($container_stub, $kernel_stub)
    {
        $template_path = realpath(__DIR__.'/../../../../Column/Template/');
        $kernel_stub->locateResource(Argument::any())->willReturn($template_path);
        
        $container_stub->get(Argument::any())->willReturn($kernel_stub);
        $this->setContainer($container_stub);
    }
    
    function it_should_return_finder_for_extends()
    {
        $this->getStandard()->shouldBeAnInstanceOf('\Symfony\Component\Finder\Finder');
    }
    
    function it_should_extends_standard_templates()
    {
        $yaml = new \Symfony\Component\Yaml\Parser();
        $value = $yaml->parse(file_get_contents(realpath(__DIR__.'/../../../../Column/Template/name.yml')));
        $options = array_merge($value, $this->getOptions());
        
        $this->useDefaults($this->getOptions())->shouldBe($options);
    }
    
}
