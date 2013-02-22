<?php

namespace Smirik\PropelAdminBundle\Action;

class AjaxFlagAction extends AjaxObjectAction
{
    
    protected $template = 'SmirikPropelAdminBundle:Admin/Action:ajax_flag.html.twig';

    public function getAlias()
    {
        return 'ajax_flag';
    }
    
}
