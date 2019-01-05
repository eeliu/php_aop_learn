<?php

use PHPUnit\Framework\TestCase;

class classLoaderTest extends TestCase
{
    public function testReflection()
    {
        $obj = new ReflectionClass("Pinpoint\Plugins\CommonPlugins");
        $this->assertIsObject($obj);
    }
}