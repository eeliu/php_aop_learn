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
        /// todo start_this_aspect_trace
        $this->apid = $apid;
        $this->who =  $who;
        $this->args = $args;
    }

    public function __destruct()
    {
       ///todo stop_this_aspect_trace;
    }

    abstract function onBefore();

    abstract function onEnd(&$ret);

    abstract function onException($e);
}