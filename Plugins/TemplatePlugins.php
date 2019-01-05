<?php
/**
 * User: eeliu
 * Date: 1/4/19
 * Time: 3:23 PM
 */

namespace Pinpoint\Plugins;


class TemplatePlugins
{
    protected $apid;
    protected $who;
    protected $args;
    public function __construct($apid,$who,...$args)
    {
        $this->apid = $apid;
        $this->who =  $who;
        $this->args = $args;
    }
}