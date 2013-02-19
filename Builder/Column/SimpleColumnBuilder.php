<?php

namespace Smirik\PropelAdminBundle\Builder\Column;

use Smirik\PropelAdminBundle\Column\SimpleColumn;

class SimpleColumnBuilder implements ColumnBuilderInterface
{

    public function create($options)
    {
        $action = new SimpleColumn();
        $action->setup($options);
        return $action;
    }

}