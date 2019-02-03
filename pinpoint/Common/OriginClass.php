<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 10:28 AM
 */

namespace pinpoint\Common;


class OriginClass
{

    private $prefixClassName = 'Proxied_';

    private $appendingFile = array();

    private $prefixCacheDir;

    public function setClassPrefix($prefix,$cacheDir)
    {
        $this->prefixClassName = $prefix;
        $this->prefixCacheDir  = $cacheDir;
    }


    //
    public function setAppendingFile($file)
    {
        if(!in_array($file,$this->appendingFile))
        {
            $this->appendingFile[] = $file;
        }
    }

//    public function

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return '';
    }
}

/**
 * remove class final , rename class name
 *
 * remove class method mod ->protected
 */