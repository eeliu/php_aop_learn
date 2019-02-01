<?php
namespace Pinpoint\Plugins;
use Pinpoint\Plugins\__TemplatePlugins;

/// @hook:\app\Foo::foo_p1
class CommonPlugins extends __TemplatePlugins
{
    public function __construct($apid,$who,...$args){
        parent::__construct($apid,$who,...$args);
    }
    /// @hook:print_r
    public function onBefore(){
        echo "onBefore";
    }

    public function onEnd($ret){
        echo "onEnd";
    }
    /// @hook:connect
    public function onException(){
        echo "catch excepton";
    }
}