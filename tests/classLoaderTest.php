<?php
/**
 * Created by PhpStorm.
 * User: liumingyi
 * Date: 1/3/19
 * Time: 11:27 AM
 */

use PHPUnit\Framework\TestCase;

class classLoaderTest extends TestCase
{
    public function testReflection()
    {
        $obj = new ReflectionClass("Pinpoint\Plugins\CommonPlugins");
        $this->assertIsObject($obj);
    }



}