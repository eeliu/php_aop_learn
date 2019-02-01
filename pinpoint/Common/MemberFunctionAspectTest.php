<?php
/**
 * User: eeliu
 * Date: 2/1/19
 * Time: 2:29 PM
 */

namespace pinpoint\Common;
require_once __DIR__. '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use pinpoint\Common\MemberFunctionAspect;

class MemberFunctionAspectTest extends TestCase
{

    public function testOnBefore()
    {

    }

    public function testOnEnd()
    {

    }

    public function testOnException()
    {

    }

    public function test__construct()
    {
        $var =  new MemberFunctionAspect("\app\Foo::foo_p1","\Pinpoint\Plugins\CommonPlugins");

    }
}
