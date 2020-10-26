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
        $ua2 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.80 Safari/537.36';
        $this->parser->setUserAgent($ua2);

        $this->assertTrue($this->parser->isMacintosh());

        $this->assertFalse($this->parser->isWindows());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Chrome', $this->parser->getBrowserName());
        $this->assertEquals('Mac OS X', $this->parser->getSystemName());

        // print_r($this->parser->result());
    }

    public function testFirefox()
    {
        $ua2 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0';
        $this->parser->setUserAgent($ua2);
        ////print_r($this->parser->result());

        $this->assertTrue($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isWindows());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());
        $this->assertEquals('Firefox', $this->parser->getBrowserName());
        $this->assertEquals('81.0', $this->parser->getBrowserVersion());
        $this->assertEquals('Mac OS X', $this->parser->getSystemName());
        $this->assertEquals('10.15', $this->parser->getSystemVersion());
    }

    public function testOpera()
    {
        $ua2 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36 OPR/71.0.3770.271';
        $this->parser->setUserAgent($ua2);
        // print_r($this->parser->result());

        $this->assertTrue($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isWindows());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Opera', $this->parser->getBrowserName());
        $this->assertEquals('71.0.3770.271', $this->parser->getBrowserVersion());
        $this->assertEquals('Mac OS X', $this->parser->getSystemName());
        $this->assertEquals('10_15_7', $this->parser->getSystemVersion());
    }

    public function testSafari()
    {
        $ua2 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15';
        $this->parser->setUserAgent($ua2);
         print_r($this->parser->result());

        $this->assertTrue($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isWindows());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMobile());

        $this->assertEquals('Safari', $this->parser->getBrowserName());
        $this->assertEquals('605.1.15', $this->parser->getBrowserVersion());
        $this->assertEquals('Mac OS X', $this->parser->getSystemName());
        $this->assertEquals('10_15_7', $this->parser->getSystemVersion());
    }
}