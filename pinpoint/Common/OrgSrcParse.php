<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 4:35 PM
 */

namespace pinpoint\Common;

use pinpoint\Common\OriginClass;
use pinpoint\Common\ShadowClass;

class OrgSrcParse
{
    private $className;
    private $monitoredFunc;
    private $originFile;

    public function __construct($file,$cl,$info)
    {
        $this->className = $cl;
        $this->monitoredFunc = $info;
        $this->originFile = $file;
    }

    protected function parseOriginFile()
    {

    }

    public function getOriginClass(){

    }

    public function getShadowClass(){

    }

    public function generateAllClass():array
    {
        /// ast to source
        return array("shadow","origin");
    }
}