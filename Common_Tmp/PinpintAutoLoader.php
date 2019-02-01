<?php
namespace Pinpoint;



class PinpointAutoLoader
{
    public function __construct()
    {
        spl_autoload_register(array("Pinpoint\PinpointAutoLoader","loadClassLoader"), true, true);
    }

    public static function loadClassLoader($class)
    {
        echo $class."\n";
    }
    public function findFile($class)
    {

    }
}

return new PinpointAutoLoader();