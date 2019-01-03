<?php
/**
 * Created by PhpStorm.
 * User: liumingyi
 * Date: 1/2/19
 * Time: 5:50 PM
 */

namespace Pinpoint\Plugins;


/// @hook:hello,hello_aop,hello_php-parse
/// @hook:Illuminate\Bus\Dispatcher::dispatchFromArray,Illuminate\Bus\Dispatcher::notexist
/// @hook:
class CommonPlugins
{
    public $apid;
    public $name;

    ///@hook:print_t,fprintf
    public function onBefore($Who,$args){
        echo "onBefore";
    }
    ///@hook:close,close1
    public function onEnd($Who,$args,$ret){
        echo "onEnd";
    }
    ///@hook:fopen
    public function onException($Who,$args){
        echo "catch excepton";
    }
}