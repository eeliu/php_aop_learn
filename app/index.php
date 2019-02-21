<?php
/**
 * User: eeliu
 * Date: 1/4/19
 * Time: 2:29 PM
 */

require_once __DIR__. '/../pinpoint/auto_pinpointed.php';

use \app\Foo;

$f = new Foo();

echo ($f->foo_p3(1,'2','3435'));

echo $f->foo_p1("placeholder");