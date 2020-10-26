<?php

namespace Cnwyt\UserAgentParser\Tests;

use Cnwyt\UserAgentParser\UserAgentParser;
use PHPUnit\Framework\TestCase;

final class PhoneUATest extends TestCase
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

    public function testAndroid()
    {
        $ua = 'Mozilla/5.0 (Linux; Android 8.1.0; vivo NEX A Build/OPM1.171019.019; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36 VivoBrowser/5.5.4.2';

        $this->parser = new UserAgentParser($ua);

        //print_r($this->parser->result());
        $this->assertTrue($this->parser->isMobile());
        $this->assertTrue($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isWindows());
        $this->assertFalse($this->parser->isIOS());

        $this->assertEquals('Android', $this->parser->getSystemName());
        $this->assertEquals('8.1.0', $this->parser->getSystemVersion());
        $this->assertEquals('VivoBrowser', $this->parser->getBrowserName());
        $this->assertEquals('5.5.4.2', $this->parser->getBrowserVersion());
        $this->assertEquals('vivo NEX', $this->parser->getDeviceName());
    }

    public function testIOS()
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1';
        $this->parser = new UserAgentParser($ua);

        //print_r($this->parser->result());
        $this->assertTrue($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMacintosh());

        $this->assertContains('iPhone', $this->parser->getSystem());
        $this->assertContains('13_2_3', $this->parser->getSystemVersion());
        $this->assertEquals('Safari', $this->parser->getBrowserName());
        $this->assertEquals('604.1', $this->parser->getBrowserVersion());
        $this->assertEquals('iPhone', $this->parser->getDeviceName());
    }
}