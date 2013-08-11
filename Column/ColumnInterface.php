<?php

namespace Smirik\PropelAdminBundle\Column;

interface ColumnInterface
{

    /**
     * Visible in the table view
     * @return bool
     */
    function isListable();
    /**
     * Shoule be editable
     * @return bool
     */
    function isEditable();
    /**
     * Add sorting to the table view
     * @return bool
     */
    function isSortable();
    /**
     * Add filtering to the table view
     * @return bool
     */
    function isFilterable();

    function getName();
    function getAlias();
    function setup($options);

}