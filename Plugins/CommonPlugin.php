<?php
namespace Pinpoint\Plugins;
use Pinpoint\Plugins\Candy;

///@hook:app\Foo::foo_p1 app\Foo::foo_p3_rbool app\Foo::foo_p3_rfloat
/// @hook:test wrong format
class CommonPlugin extends Candy
{
    public function __construct($apid,$who,...$args){
        parent::__construct($apid,$who,...$args);
    }

    ///@hook:app\DBcontrol::connectDb
    public function onBefore(){
        echo "onBefore";
    }

    ///@hook:app\DBcontrol::getData1
    public function onEnd(&$ret){
        if($ret === false)
        {
            $ret=true;
        }
    }

    ///@hook:app\DBcontrol::getData2
    public function onException($e){
        echo "catch excepton";
    }
}