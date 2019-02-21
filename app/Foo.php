<?php
/**
 * User: eeliu
 * Date: 1/31/19
 * Time: 10:27 AM
 */

namespace app;


class Foo
{
    /// test 0 parameter
    public function foo0()
    {

    }
    /// test 1 parameter
    public function foo_p1($p)
    {
        $ch = \curl_init();

        //        $ch = call_user_func('curl_init');

        // ÉèÖÃURLºÍÏàÓ¦µÄÑ¡Ïî
        //curl_setopt($ch, CURLOPT_URL, "http://www.example.com/");
        \call_user_func_array(curl_setopt,array($ch, CURLOPT_URL, "http://www.example.com/"));

        \curl_setopt($ch, CURLOPT_HEADER, 0);


        \curl_exec($ch);

        \curl_close($ch);
        return $ch;
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
        $args = func_num_args();
        echo __METHOD__.":count=".count($args)."\n";
        if(true)
            throw new \Exception("");
        return false;
    }

    /// test 1 parameter
    public function foo_p3_rfloat($p1,$p2,$p3):float
    {
        return true;
    }
}
