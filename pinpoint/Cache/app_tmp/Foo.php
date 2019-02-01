<?php
/**
 * User: eeliu
 * Date: 1/31/19
 * Time: 10:27 AM
 */

namespace app;
use Pinpoint\Plugins\CommonPlugins;

class Foo extends Proxied_Foo
{
    /// test 0 parameter
    public function foo0()
    {
        $var =  new CommonPlugins('\app\Foo\foo0',$this,null);
        try
        {
            $var->onBefore();
            parent::foo0();
        }catch (Exception $e)
        {
            $var->onException($e);
        }
        finally
        {
            $var->onEnd();
        }
    }
    /// test 1 parameter
    public function foo_p1($p)
    {

    }

    /// test 1 parameter
    public function foo_p2($p1,$p2)
    {

    }

    /// test 1 parameter
    public function foo_p3($p1,$p2,$p3)
    {

    }

    /// test 1 parameter
    public function foo_p3_rbool($p1,$p2,$p3):bool
    {
        return true;
    }

    /// test 1 parameter
    public function foo_p3_rfloat($p1,$p2,$p3):float
    {
        return true;
    }
}