<?php

namespace Cnwyt\UserAgentParser;

class UserAgentParser implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $version = '0.4.0';

    protected $userAgent = '';
    protected $browser = [];
    protected $system = [];
    protected $device = [];
    protected $netType = '';

    protected $matchPattern = null;
    protected $matchArray = [];

    public function jsonSerialize()
    {
        return [
            'brower' => $this->browser,
            'system' => $this->system,
            'device' => $this->device,
            'netType' => $this->netType,
        ];
    }

    protected function pregMatch($regex, $userAgent = null)
    {
        $match = (bool)preg_match(sprintf('#%s#is', $regex), $this->userAgent, $matches);
        if ($match) {
            $this->matchPattern = $regex;
            $this->matchArray = $matches;
        }

        return $match;
    }

    /**
     * Get the platform name.
     *
     * @param  string $userAgent
     * @return string
     */
    public function platform($userAgent = null)
    {

    }

    /**
     * set user agent
     * @param string $userAgent
     * @return string
     */
    public function setUserAgent($userAgent = '')
    {
        return $this->userAgent = trim($userAgent);
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function isMobile()
    {
    }

    protected $versionRegex = '([\w._\+]+)';

    protected $deviceRules = [
        // mobile phone
        'Huawei' => 'HUAWEISTF',
        'iPhone' => '\biPhone\b|\biPod\b',
        'OPPO R11st' => 'OPPO R11st',
        'OPPO' => 'OPPO',
        'SAMSUNG-G900P' => 'SM-G900P',
        'SAMSUNG-N900T' => 'SM-N900T',
        'Pixel 2 XL' => 'Pixel 2 XL',
        'Pixel 2' => 'Pixel 2',
        'Nexus' => 'Nexus',
        'Lumia 520' => 'Lumia 520',
        'NOKIA' => 'NOKIA',

        // tablet
        'iPad' => 'iPad|iPad.*Mobile',

        'MacOSX(Intel)' => 'Intel Mac OS X',
        'Mac' => 'Macintosh',
    ];

    protected $systemRules = [
        // (Linux; U; Android 4.4.4; zh-cn; 2014811 Build/KTU84P)
        'Android' => 'Android\ ([\w._\+]+);',
        'AndroidOS' => 'Android',
        // (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) 
        'iPhone iOS' => 'iPhone OS (\w+_\w+_\w+)',
        // (iPad; CPU OS 11_0 like Mac OS X)
        // (iPad; CPU OS 11_0 like Mac OS X)
        'iPad iOS' => 'iPad; CPU OS (\w+_\w+) like Mac OS X',
        'iOS' => '\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia',

        // 'Mac OS X' => 'Mac OS X',
        // (Macintosh; Intel Mac OS X 10_13_6)
        'Mac OS X' => 'Mac\ OS\ X\ (\w+_\w+_\w+)',
        'MacOSX' => 'Mac OS X',

        'Windows Phone' => 'Windows Phone',
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];

    protected $browserRules = [
        'MQQBrowser' => 'MQQBrowser/VER', // QQBrowser mobile
        'QQ-MttCustomUA' => 'MttCustomUA/VER', // QQBrowser mobile CustomUA
        'MicroMessenger' => 'MicroMessenger/VER', // Wechat(MicroMessenger)

        'BaiduApp' => 'baiduboxapp/VER',
        'baidubrowser' => 'baidubrowser/VER',
        'Chrome CriOS' => 'CriOS/VER',
        'Firefox FxiOS' => 'FxiOS/VER',
        'Opera OPiOS' => 'OPiOS/VER',
        'Opera' => 'OPR/VER',
        'UCQuark' => 'Quark/VER', // UCBrowser Quark
        'UCBrowser' => 'UCBrowser/VER',
        'UCWEB' => 'UCWEB/VER',

        'QihooBrowser' => 'QihooBrowser/VER', // 360Browser
        'QHBrowser' => 'QHBrowser/VER', // 360Browser

        'SogouMobileBrowser' => 'SogouMobileBrowser/VER', // SogouBrowser

        'MaxthonMXiOS' => 'MXiOS/VER',
        'Maxthon' => 'Maxthon/VER',
        'Edge' => 'Edge/VER',
        'YaBrowser' => 'YaBrowser/VER', // Yandex Browser (Yowser)
        'Vivaldi' => 'Vivaldi/VER',
        'Chrome' => 'Chrome/VER',
        'Firefox' => 'Firefox/VER',
        'Safari' => 'Safari/VER',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Bolt' => 'bolt/VER',
        'TeaShark' => 'teashark/VER',
        'Blazer' => 'Blazer/VER',
        'Mozilla' => 'Mozilla/VER',
    ];

    public function getSystem()
    {
        foreach ($this->systemRules as $key => $regex) {
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $this->system = [
                    'name' => $key,
                    'version' => $this->matchArray[1] ?? '',
                ];
                break;
            }
        }
        return $this->system;
    }

    public function getSystemName()
    {
        if (empty($this->system)) {
            $this->getSystem();
        }

        return !empty($this->system['name']) ? $this->system['name'] : 'unknown';
    }

    public function getSystemVersion()
    {
        if (empty($this->system)) {
            $this->getSystem();
        }

        return !empty($this->system['version']) ? $this->system['version'] : 'unknown';
    }

    public function getDevice()
    {
        foreach ($this->deviceRules as $key => $regex) {
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $this->device = [
                    'name' => $key,
                    'version' => $this->matchArray[1] ?? '',
                ];
                break;
            }
        }
        return $this->device;
    }

    public function getDeviceName()
    {
        if (empty($this->device)) {
            $this->getDevice();
        }

        return !empty($this->device['name']) ? $this->device['name'] : 'unknown';
    }

    public function getBrowserName()
    {
        if (empty($this->browser)) {
            $this->getBrowser();
        }

        return !empty($this->browser['name']) ? $this->browser['name'] : 'unknown';
    }

    public function getBrowserVersion()
    {
        if (empty($this->browser)) {
            $this->getBrowser();
        }

        return !empty($this->browser['version']) ? $this->browser['version'] : '';
    }

    public function getBrowser()
    {
        foreach ($this->browserRules as $key => $regex) {
            $regex = str_ireplace('VER', $this->versionRegex, $regex);
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $this->browser = [
                    'name' => $key,
                    'version' => $this->matchArray[1] ?? '',
                ];
                break;
            }
        }
        return $this->browser;
    }


    public function isIOS()
    {
        return stripos($this->userAgent, 'iPhone') !== false;
    }

    public function isAndroidOS()
    {
        return stripos($this->userAgent, 'Android') !== false;
    }

    public function isWechatBrowser()
    {
        return stripos($this->userAgent, 'MicroMessenger') !== false;
    }
    public function getWechatVersion()
    {
        if ($this->pregMatch('MicroMessenger/' . $this->versionRegex, $this->userAgent)) {
            return $this->matchArray[1] ?? '';
        }

        return false;
    }

}
