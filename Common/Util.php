<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 2:11 PM
 */

namespace Pinpoint\Common;



use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


class Util
{
    private static $logger ;
    /**
     * Util constructor.
     */
    public static function init()
    {
        Util::$logger = new Logger("pinpoint");
    }

    public  static function scanDir($folder, $pattern_array)
    {
        $return = array();
        $iti = new RecursiveDirectoryIterator($folder);
        foreach(new RecursiveIteratorIterator($iti) as $file){
            if (in_array(strtolower(array_pop(explode('.', $file))), $pattern_array)){
                $return[] = $file;
            }
        }
        return $return;
    }

    public static function getMajorVersion()
    {
        //todo
        return '7';
    }


}
