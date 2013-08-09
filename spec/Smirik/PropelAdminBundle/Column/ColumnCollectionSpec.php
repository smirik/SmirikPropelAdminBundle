<?php

namespace spec\Smirik\PropelAdminBundle\Column;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnCollectionSpec extends ObjectBehavior
{
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Smirik\PropelAdminBundle\Column\ColumnCollection');
    }
    
    /**
     * @param \Smirik\PropelAdminBundle\Column\SimpleColumn $column1
     * @param \Smirik\PropelAdminBundle\Column\FileColumn $column2
     * @param \Smirik\PropelAdminBundle\Column\FileColumn $column3
     */
    function let($column1, $column2, $column3)
    {
        $column1->getAlias()->willReturn('simple');
        $column2->getAlias()->willReturn('file');
        $column3->getAlias()->willReturn('file');

        $this->setColumns(array('column1' => $column1, 'column2' => $column2, 'column3' => $column3));
    }
    
    function it_should_have_three_columns()
    {
        $this->has('column1')->shouldBe(true);
        $this->has('column2')->shouldBe(true);
        $this->has('column3')->shouldBe(true);
        $this->has('column4')->shouldBe(false);
    }
    
    function it_should_have_two_file_column()
    {
        $this->getFileColumns()->shouldHaveCount(2);
    }
    
}
