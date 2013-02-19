<?php

namespace Smirik\PropelAdminBundle\Builder\Column;

interface ColumnBuilderInterface
{

    /**
     * Creates column related the the options
     * @param array $options
     * @return void
     */
    function create($options);

}