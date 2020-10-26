<?php

namespace Cnwyt\UserAgentParser\Tests;

use Cnwyt\UserAgentParser\UserAgentParser;
use PHPUnit\Framework\TestCase;

final class WindowsUATest extends TestCase
{
    protected $parser = null;

    public function setUp()
    {
        $this->parser = new UserAgentParser();
    }
    public function testIndex()
    {
        $this->assertTrue(true);
    }

    public function testChrome()
    {
        $ua2 = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36';
        $this->parser->setUserAgent($ua2);

//         print_r($this->parser->result());

        $this->assertTrue($this->parser->isWindows());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Windows8', $this->parser->getSystemName());
        $this->assertEquals('6.2', $this->parser->getSystemVersion());
        $this->assertEquals('Chrome', $this->parser->getBrowserName());
        $this->assertEquals('27.0.1453.94', $this->parser->getBrowserVersion());
        $this->assertEquals('Windows', $this->parser->getDeviceName());
    }

    public function testFirefox()
    {
        $ua2 = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0';
        $this->parser->setUserAgent($ua2);
        $this->assertTrue($this->parser->isWindows());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Firefox', $this->parser->getBrowserName());
        $this->assertEquals('21.0', $this->parser->getBrowserVersion());
        $this->assertEquals('Windows8', $this->parser->getSystemName());
        $this->assertEquals('6.2', $this->parser->getSystemVersion());
        $this->assertEquals('Windows', $this->parser->getDeviceName());
    }

    public function testOpera()
    {
        $ua2 = 'Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.62';
        $this->parser->setUserAgent($ua2);
        print_r($this->parser->result());

        $this->assertTrue($this->parser->isWindows());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Opera', $this->parser->getBrowserName());
        $this->assertEquals('9.80', $this->parser->getBrowserVersion());
        $this->assertEquals('Windows7', $this->parser->getSystemName());
        $this->assertEquals('6.1', $this->parser->getSystemVersion());
        $this->assertEquals('Windows', $this->parser->getDeviceName());
    }
}