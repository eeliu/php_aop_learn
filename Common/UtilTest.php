<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 2:20 PM
 */
namespace Pinpoint\Common;

use PHPUnit\Framework\TestCase;



class UtilTest extends TestCase
{
    public function testScanDir()
    {
       $this->assertNotEmpty(Util::scanDir(".",["php"]));
    }

    public function testGetMajorVersion()
    {

    }
}
