<?php
/**
 * User: eeliu
 * Date: 1/4/19
 * Time: 3:23 PM
 */

namespace Pinpoint\Plugins;


abstract class Candy
{
    protected $apId;
    protected $who;
    protected $args;
    protected $ret=null;
    public function __construct($apId,$who,&...$args)
    {
        /// todo start_this_aspect_trace
        $this->apId = $apId;
        $this->who =  $who;
        $this->args = &$args;
    }

    public function __destruct()
    {
       ///todo stop_this_aspect_trace;
    }

    abstract function onBefore();

    abstract function onEnd(&$ret);

    abstract function onException($e);
}