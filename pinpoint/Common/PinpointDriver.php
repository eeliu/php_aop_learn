<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 5:14 PM
 */

namespace pinpoint\Common;
use pinpoint\Common\OrgClassParse;
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
        $this->clAr = [];

    }

    public function init()
    {

        if(file_exists($this->Cfg['class_index_file']))
        {
            $this->classMap =  new ClassMap($this->Cfg['class_index_file']);
            $this->classMap->debug();
            AopClassLoader::init($this->classMap->classMap);
            return ;
        }

        $this->classMap =  new ClassMap();
        //parse the plugins
        $pluFiles = glob($this->Cfg['plugin_path']."/*Plugin.php");
        $pluParsers = [];
        foreach ($pluFiles as $file)
        {
            $pluParsers[] = new PluginParser($file,$this->clAr);
        }

        foreach ($this->clAr as $cl=> $info)
        {
            echo $cl;
            print_r($info);

            $file = Util::findFile($cl);
            if(is_null($file))
            {
                //todo logging $cl and $file
                echo $file.' '.$cl."\n";
                continue;
            }

            $osr = new OrgClassParse($file,$cl,$info,$this->Cfg);
            foreach ($osr->classIndex as $clName=>$path)
            {
                $this->classMap->insertMapping($clName,$path);
            }
        }

//        $this->classMap->persistenceClassMapping($this->Cfg['class_index_file']);

        $this->classMap->debug();

        AopClassLoader::init($this->classMap->classMap);

    }


}