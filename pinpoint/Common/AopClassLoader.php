<?php

namespace pinpoint\Common;

use Composer\Autoload\ClassLoader;

require_once __DIR__. '/../../vendor/autoload.php';

class AopClassLoader //extends ClassLoader
{
    public  static $inInitalized;
    private $origin;
    private $classMap;
    public function __construct($classIndexFile)
    {
        if($classIndexFile)
        {
            /// todo do thing
        }
    }

    public function findFile($class)
    {
        $file = isset($this->classMap[$class]) ? $this->classMap[$class] : null;
        if( is_null($file )) {
            $file = $this->origin->findFile($class);
            if ($file !== false)
            {
                $file = realpath($file) ?: $file;
                $this->classMap[$class] = $file;
            }
        }
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

//        var_dump(spl_autoload_functions());

        return self::$inInitalized;
    }

}

//AopClassLoader::init();