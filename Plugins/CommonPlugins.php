<?php
namespace Pinpoint\Plugins;
use Pinpoint\Plugins\__TemplatePlugins;

///@hook:app\Foo::foo_p1
/// @hook:test wrong format
class CommonPlugins extends __TemplatePlugins
{
    public function __construct($apid,$who,...$args){
        parent::__construct($apid,$who,...$args);
    }

    ///@hook:app\Foo::print_r
    ///@hook:app\Foo::curl_init
    public function onBefore(){
        echo "onBefore";
    }

    ///@hook:app\Foo::curl_init app\Foo::curl_setopt
    public function onEnd($ret){
        echo "onEnd";
    }

    ///@hook:app\Foo::curl_init
    public function onException(){
        echo "catch excepton";
    }
}