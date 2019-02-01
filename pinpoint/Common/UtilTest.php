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

class UtilTest extends TestCase
{

    public function testFindFile()
    {
        self::assertEquals(Util::findFile(UtilTest::class),__FILE__);
    }
}
