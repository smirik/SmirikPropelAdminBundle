<?php

namespace Smirik\PropelAdminBundle\Action;

interface ActionInterface
{
    /**
     * Get an array of options from config (default) or custom (from builder)
     * @param array
     * @return void
     */
    function setup($options);
    
    /**
     * Name of route showing in template 
     * @return string
     */
	function getLabel();
    
    /**
     * Route for action (with id if options are enabled) 
     * @return string
     */
    function getRoute();
    
    /**
     * @return string 
     */
    function getAlias();
    
    /**
     * Groups actions by types 
     * @return string
     */
    function getType();

}