<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 5:14 PM
 */

namespace pinpoint\Common;
use pinpoint\Common\OrgSrcParse;
use pinpoint\Common\AopClassLoader;
use pinpoint\Common\ClassMap;

class PinpointDriver
{
    protected static $instance;
    protected $Cfg;
    protected $clAr;
    protected $classMap;

    /**
     * @return mixed
     */
    public function getClassMap()
    {
        return $this->classMap;
    }

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
        $this->clAr = array();
        $this->classMap =  new ClassMap($this->Cfg['class_index']);

    }

    public function init()
    {

        //todo read __class_index to register  classloader

        //parse the plugins
        $pluFiles = glob($this->Cfg['plugin_path']."/*Plugin.php");

        $pluParsers = array();
        foreach ($pluFiles as $file)
        {
            $pluParsers[] = new PluginParser($file,$this->clAr);
        }

        foreach ($this->clAr as $cl=> $info)
        {
            // - get cl_file

            $file = Util::findFile($cl);

            if(is_null($file))
            {
                //todo logging $cl and $file
                echo $file.' '.$cl."\n";
                continue;
            }
            $osr = new OrgSrcParse($file,$cl,$info);
            list($shadow=>$shadowClassFile,$origin=>$originClassFile )= $osr->generateAllClass();

            $this->classMap->insertMapping($shadow,$shadowClassFile);
            $this->classMap->insertMapping($origin,$originClassFile);


        }

        $this->classMap->persistenceClassMapping($this->Cfg['class_index']);

        AopClassLoader::init($this->classMap);

    }


}