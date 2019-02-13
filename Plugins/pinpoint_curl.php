<?php
/**
 * User: eeliu
 * Date: 2/11/19
 * Time: 4:08 PM
 */

namespace pinpoint\internal_template;

class pinpoint_curl{
    private $ch;
    function __construct()
    {
        $this->ch = call_user_func("curl_init");
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