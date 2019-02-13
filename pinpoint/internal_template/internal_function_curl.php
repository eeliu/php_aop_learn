<?php
/**
 * User: eeliu
 * Date: 2/11/19
 * Time: 5:33 PM
 */

/**
 *  namespace must be redefined
 */

use pinpoint_curl;

function curl_init()
{
    return new pinpoint_curl();
}

function  curl_setopt( $pc , $option , $value ) : bool
{
    assert($pc instanceof pinpoint_curl);
    return $pc->curl_setopt($option, $value);
}

function curl_exec($pc)
{
    assert($pc instanceof pinpoint_curl);
    return $pc->curl_exec();
}

function curl_close($pc)
{
    assert($pc instanceof pinpoint_curl);
    return $pc->curl_close();
}

//$pc =  new pinpoint_curl();
//
//
//$pc->curl_setopt(CURLOPT_URL, "http://www.example.com/");
//
//$pc->curl_setopt(CURLOPT_HEADER, 0);
//
//
//$pc->curl_setopt(CURLOPT_HEADER, 0);
//
//
//
//$pc->curl_exec();
//
//$pc->curl_close();