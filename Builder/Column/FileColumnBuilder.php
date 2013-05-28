<?php

namespace Smirik\PropelAdminBundle\Builder\Column;

use Smirik\PropelAdminBundle\Column\FileColumn;

class FileColumnBuilder implements ColumnBuilderInterface
{

    public function create($options)
    {
        $action = new FileColumn();
        $action->setup($options);
        return $action;
    }

}