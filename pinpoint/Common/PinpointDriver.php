<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 5:14 PM
 */

namespace pinpoint\Common;


class PinpointDriver
{
    private static $instance;
    private $Cfg;
    public static function getInstance(){

        if (!self::$instance) {
            $Cfg  = require __DIR__."/../pinpoint_config.php";
            self::$instance = new static($Cfg);
        }

        return self::$instance;
    }

    public function __construct($Cfg)
    {
        $this->Cfg = $Cfg;
    }

    public function init()
    {

        //todo register  classloader

        $pluFiles = glob($this->Cfg['plugin_path']."/*Plugin.php");

        $pluParsers = array();
        foreach ($pluFiles as $file)
        {
            $pluParsers[] = new PluginParser($file);
        }

        foreach ($pluParsers as $pluParser)
        {
            
        }

        //todo checking __class_index => class_loader

        //todo read plugins
                // parse plugins

        //todo rendering code

        //todo update __class_index.php

        //todo update class_loder
    }


}