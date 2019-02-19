<?php
namespace Test;

class pinpoint_curl{
    const module='curl';

    private $ch;

    public function  curl_init($curl)
    {
        return $ch = \curl_init($curl);
    }

    function __call($name, $arguments)
    {
        // todo add CURLOPT_HEADER monitor pinpoint header
        // use call system function
        array_unshift($arguments,$this->ch);
//        var_dump($arguments);
        return call_user_func_array($name,$arguments);
    }
}


/// todo use php-parse to insert other functions

function curl_init()
{

//    $p = new curlPlugins();

//    $p->onBefore();
    $ret = call_user_func('curl_init');

//    $p->onEnd($ret);
    return $ret;
}