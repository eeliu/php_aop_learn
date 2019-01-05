<?php
/**
 * User: eeliu
 * Date: 1/4/19
 * Time: 2:29 PM
 */

require_once __DIR__. '/../vendor/autoload.php';

use app\DBcontrol as DBcontrol;
$loader = require_once __DIR__. '/../Common/PinpintAutoLoader.php';

$db = new DBcontrol("dev-test-mysql:3306","root","root");

$db->connectDb();

var_dump($db->getData1());
var_dump($db->getData2());