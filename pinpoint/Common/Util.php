<?php
/**
 * User: eeliu
 * Date: 2/1/19
 * Time: 4:21 PM
 */

namespace pinpoint\Common;
use Composer\Autoload\ClassLoader;

class Util
{
    private static $origin_class_loader;

    public static function findFile($class)
    {
        if(is_null(Util::$origin_class_loader))
        {
            $loaders = spl_autoload_functions();
            foreach ($loaders as $loader) {
                if(is_array($loaders)&& $loader[0] instanceof ClassLoader)
                {
                    Util::$origin_class_loader = $loader[0];
                    break;
                }
            }
        }

        return realpath(Util::$origin_class_loader->findFile($class));
    }

}