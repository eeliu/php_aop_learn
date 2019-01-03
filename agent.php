<?php

//$loader = require_once __DIR__ . '/Common/PinpintAutoLoader.php';

//require_once __DIR__."/vendor/autoload.php";

$loader = require_once __DIR__."/vendor/autoload.php";

define("APP_CODE_ROOT","");
define("VENDOR_ROOT","");
define("PINPOINT_ROOT",__DIR__.'/../');

echo $loader->findFile("Pinpoint\Pluginsl\CommonPlugins");

//use Pinpoint\Plugins\CommonPlugins;
//
//$obj = new ReflectionClass("Pinpoint\Plugins\CommonPlugins");
//
//echo $obj;