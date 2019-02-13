<?php
/**
 * User: eeliu
 * Date: 1/4/19
 * Time: 3:23 PM
 */

namespace Pinpoint\Plugins;


abstract class Candy
{
    protected $apid;
    protected $who;
    protected $args;
    protected $ret=null;
    public function __construct($apid,$who,...$args)
    {
        $this->apid = $apid;
        $this->who =  $who;
        $this->args = $args;
    }

    abstract function onBefore();

    abstract function onEnd($ret);

    abstract function onException($e);
}