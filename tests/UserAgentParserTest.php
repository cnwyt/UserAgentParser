<?php

namespace Cnwyt\UserAgentParser\Tests;

use Cnwyt\UserAgentParser\UserAgentParser;
use PHPUnit\Framework\TestCase;

final class UserAgentParserTest extends TestCase
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

    public function testMacintosh()
    {
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3534.4 Safari/537.36';
        $this->parser = new UserAgentParser($ua);
        $this->assertTrue($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isWindows());

        $ua2 = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36 OPR/54.0.2952.64';
        $this->parser->setUserAgent($ua2);
        $this->assertTrue($this->parser->isMacintosh());
        $this->assertFalse($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertEquals('Opera', $this->parser->getBrowserName());

    }

    public function testAndroid()
    {
        $ua = 'Mozilla/5.0 (Linux; Android 8.0; SM-G9500 Build/R16NW; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/6.2 TBS/044207 Mobile Safari/537.36 MicroMessenger/6.7.2.1340(0x2607023A) NetType/WIFI Language/zh_CN';
        $this->parser = new UserAgentParser($ua);
        $this->assertTrue($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertContains('SamSung', $this->parser->getDeviceName());
        $this->assertEquals('MicroMessenger', $this->parser->getBrowserName());
        $this->assertEquals('6.7.2.1340', $this->parser->getBrowserVersion());

        $ua2 = 'Mozilla/5.0 (Linux; Android 8.1.0; vivo NEX A Build/OPM1.171019.019; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36 VivoBrowser/5.5.4.2';
        $this->parser->setUserAgent($ua2);
        $this->assertTrue($this->parser->isAndroidOS());
        $this->assertFalse($this->parser->isIOS());
        $this->assertFalse($this->parser->isMacintosh());
        $this->assertContains('vivo', $this->parser->getDeviceName());
        $this->assertEquals('VivoBrowser', $this->parser->getBrowserName());
        $this->assertEquals('5.5.4.2', $this->parser->getBrowserVersion());
    }
}