<?php
namespace Pinpoint\Plugins;

/// @hook:app\DBcontrol::getData1
class CommonPlugins extends TemplatePlugins
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

/**
 * use Pinpoint\Plugins\CommonPlugins as CommonPlugins;
 * $var = new CommonPlugins("app\DBcontrol::getData1",$this,...$args);
 * $var->onBefore();
 * try{
 *  //todo if origin_getData1 has return
 * $ret = $this->origin_getData1(...$args);
 * }catch(Exception $e)
 * {
 * $var->enException();
 * }
 * // or $var->onEnd();
 * $var->onEnd($ret);
 *  return $ret;
 */