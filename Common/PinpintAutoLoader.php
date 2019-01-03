<?php
/**
 * Created by PhpStorm.
 * User: liumingyi
 * Date: 1/2/19
 * Time: 6:31 PM
 */

namespace Pinpoint;


class PinpointAutoLoader
{
    public function __construct()
    {
        spl_autoload_register(array("Pinpoint\PinpointAutoLoader","loadClassLoader"), true, true);
    }

    public static function loadClassLoader($class)
    {
        // if start with Pinpoint
        if (strpos($class,'Pinpoint') == 0) {
            $class = str_replace('\\','/',$class);
            $path =  constant("PINPOINT_ROOT")?:__DIR_."../../";
            echo "include " . $path.$class.".php \n";
            require_once $path.$class.'.php';
        }
    }
    public function findFile($class)
    {
        if (strpos($class,'Pinpoint') == 0) {
            $class = str_replace('\\','/',$class);
            $path =  constant("PINPOINT_ROOT")?:__DIR_."../../";
            return $path.$class.'.php';
        }
        return null;
    }
}

spl_autoload_register(array("Pinpoint\PinpointAutoLoader","loadClassLoader"), true, true);


return new PinpointAutoLoader();