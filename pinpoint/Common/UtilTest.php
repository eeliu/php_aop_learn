<?php
/**
 * User: eeliu
 * Date: 2/1/19
 * Time: 4:27 PM
 */

namespace pinpoint\Common;
require_once __DIR__. '/../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use pinpoint\Common\Util;
use Pinpoint\Plugins\CommonPlugin;

class UtilTest extends TestCase
{

    public function testFindFile()
    {
        self::assertEquals(Util::findFile(UtilTest::class),__FILE__);
        self::assertStringEndsWith('php', Util::findFile(CommonPlugin::class));
    }
    public function testparseUserFunc()
    {
        $str = '///@hook:\app\Foo::foo_p2 \app\Foo::foo_p1';
        self::assertIsArray(Util::parseUserFunc($str));
        self::assertIsNotArray(Util::parseUserFunc('\app\Foo::foo_p2 \app\Foo::foo_p1'));
        self::assertIsNotArray(Util::parseUserFunc(''));
        self::assertIsNotArray(Util::parseUserFunc('I don\'t now nothing'));
    }
}
