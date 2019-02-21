<?php

namespace pinpoint\Common;

use Composer\Autoload\ClassLoader;

require_once __DIR__. '/../../vendor/autoload.php';

class AopClassLoader //extends ClassLoader
{
    public  static $inInitalized;
    private $origin; //  origin classloader
    private $classMap;
    public function __construct($origin, $classMap)
    {
        if($classMap)
        {
            /// todo do thing
        }

        $this->classMap = $classMap;

        $this->origin = $origin;
    }

    public function findFile($class)
    {
        $file = $this->classMap[$class];
        if( is_null($file )) {
            $file = $this->origin->findFile($class);
            if ($file !== false)
            {
                $file = realpath($file) ?: $file;
                $this->classMap[$class] = $file;
            }
        }
//        echo "findFile : $file\n";
        return $file;

    }

    public function loadClass($class)
    {
        $file = $this->findFile($class);

        if ($file !== false) {
            include $file;
        }
    }

    public function persistenceSelf($fileName)
    {

    }

    public static  function init($classIndex)
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as &$loader) {
            $loaderToUnregister = $loader;
            if (is_array($loader) && ($loader[0] instanceof ClassLoader)) {
                $originalLoader = $loader[0];
                $loader[0] = new AopClassLoader($loader[0],$classIndex);
                self::$inInitalized = true;
            }
            spl_autoload_unregister($loaderToUnregister);
        }
        unset($loader);

        foreach ($loaders as $loader) {
            spl_autoload_register($loader);
        }

        return self::$inInitalized;
    }

}
