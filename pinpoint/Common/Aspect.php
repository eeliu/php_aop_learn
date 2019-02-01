<?php
/**
 * User: eeliu
 * Date: 2/1/19
 * Time: 2:12 PM
 */

namespace pinpoint\Common;


abstract class Aspect
{
    protected $aspect_name;
    public function __construct($name)
    {
        $this->aspect_name = $name;
    }

    abstract function onBefore();
    abstract function onEnd();
    abstract function onException();
}