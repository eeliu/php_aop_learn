<?php
namespace Pinpoint\Plugins;
use Pinpoint\Plugins\Candy;

///@hook:app\Foo::foo_p3_rbool app\Foo::foo_p3_rfloat app\Foo::foo_p3
/// @hook:test wrong format
class CommonPlugin extends Candy
{
    ///@hook:app\DBcontrol::connectDb
    public function onBefore(){
        echo $this->apId."\n";
        $this->args[0]=3;
    }

    ///@hook:app\DBcontrol::getData1
    public function onEnd(&$ret){
        if($ret === false)
        {
            $ret=true;
        }

        $ret = $this->args[2];
    }

    ///@hook:app\DBcontrol::getData2
    public function onException($e){
        echo "catch excepton";
    }
}