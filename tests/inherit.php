<?php
/**
 * User: eeliu
 * Date: 1/3/19
 * Time: 7:16 PM
 */

namespace Foo;
require_once "test.php";
use Foo\test;

function array_push(){
    echo "56";
}


array_push("fadfaf");

$test = new test();

$test->out();