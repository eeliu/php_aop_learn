<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 3:31 PM
 */

namespace Pinpoint\Common;

use Monolog\Logger as RealLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    private static $logger;

    public static function instance()
    {
        Logger::$logger = new RealLogger("pinpoint");

        Logger::$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/render.log', Logger::DEBUG));
    }


    public static function logWarning($msg)
    {
        if( empty(Logger::$logger))
        {
            Logger::instance();
        }
        Logger::$logger->warning($msg);
    }

    public static function logDebug($msg)
    {
        if( empty(Logger::$logger))
        {
            Logger::instance();
        }

        Logger::$logger->debug($msg);
    }
}