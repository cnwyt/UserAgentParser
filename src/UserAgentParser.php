<?php

namespace Cnwyt\UserAgentParser;

class UserAgentParser implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $version = '0.5.0';

    protected $userAgent = '';
    protected $browser = [];
    protected $system = [];
    protected $device = [];
    protected $netType = '';

    protected $matchPattern = null;
    protected $matchArray = [];

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'brower' => $this->browser,
            'system' => $this->system,
            'device' => $this->device,
            'netType' => $this->netType,
        ];
    }

    /**
     * @param $regex
     * @param null $userAgent
     * @return bool
     */
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
     * @param $rules
     * @return array
     */
    protected function phraseResult($rules)
    {
        $result = [];
        foreach ($rules as $key => $regex) {
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $result = [
                    'name' => $key,
                    'version' => $this->matchArray[1] ?? '',
                ];
                break;
            }
        }

        return $result;
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

    /**
     * @return bool
     */
    public function isMobile()
    {
        return $this->isIOS() || $this->isAndroidOS();
    }

    /**
     * @var string
     */
    protected $versionRegex = '([\w._\+]+)';

    /**
     * @var array
     */
    protected $deviceRules = [
        // mobile phone
        'iPhone' => '\biPhone\b|\biPod\b',
        // tablet
        'iPad' => 'iPad|iPad.*Mobile',

        'MacOSX(Intel)' => 'Intel Mac OS X',
        'Mac' => 'Macintosh',
    ];

    /**
     * @var array
     */
    protected $androidDeviceRules = [
        // mobile phone
        'Huawei' => 'HUAWEISTF',
        'HUAWEI' => 'HUAWEI',
        'OPPO R11st' => 'OPPO R11st',
        'OPPO R11' => 'OPPO R11',
        'OPPO' => 'OPPO',
        'vivo NEX' => 'vivo NEX', // vivo NEX: 2018-06-12
        'vivo' => 'vivo',
        'MI NOTE' => 'MI NOTE', // xiaomi
        'MIX' => 'MIX', // xiaomi
        'Redmi Note' => 'Redmi Note', // xiaomi: red mi
        'Redmi' => 'Redmi', // xiaomi: red mi
        'SAMSUNG' => 'SM-',  // SAMSUNG
        'SAMSUNG-G900P' => 'SM-G900P', // SAMSUNG
        'SAMSUNG-N900T' => 'SM-N900T', // SAMSUNG
        'Pixel 2 XL' => 'Pixel 2 XL',
        'Pixel 2' => 'Pixel 2',
        'Nexus' => 'Nexus',
        'TCL' => 'TCL',
        'ZUK' => 'ZUK',
        'Coolpad' => 'Coolpad',
        'Lumia 520' => 'Lumia 520',
        'NOKIA' => 'NOKIA',
    ];

    /**
     * @var array
     */
    protected $defaultSystemRules = [
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

        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];

    /**
     * @var array
     */
    protected $windowsSystemRules = [
        // NT5.0    Windows 2000
        // NT5.1    Windows XP
        // NT5.2    Windows XP, Windows Server 2003
        // NT6.0    Windows Vista, Windows Server 2008
        // NT6.1    Windows 7
        // NT6.2    Windows 8
        // NT6.3    Windows 8.1
        // NT6.4    Windows 10
        // NT10.0   Windows 10
        'Windows2000' => 'Windows NT (5.0)',
        'WindowsXP' => 'Windows NT (5.1)|Windows NT (5.2)',
        'WindowsVista' => 'Windows NT (6.0)',
        'Windows7' => 'Windows NT (6.1)',
        'Windows8' => 'Windows NT (6.2)|Windows NT (6.3)',
        'Windows10' => 'Windows NT (6.4)|Windows NT (10.0)',
        'WindowsPhone' => 'Windows Phone',
        'WindowsNT' => 'Windows NT',
        'Windows' => 'Windows',
    ];

    /**
     * @var array
     */
    protected $macintoshSystemRules = [
        // 'Mac OS X' => 'Mac OS X',
        // (Macintosh; Intel Mac OS X 10_13_6)
        'Mac OS X' => 'Mac\ OS\ X\ (\w+_\w+_\w+)',
        'MacOSX' => 'Mac OS X',
    ];

    /**
     * @var array
     */
    protected $browserRules = [
        // spider
        'Baiduspider' => 'Baiduspider',
        'Bingbot' => 'Bingbot',
        'Googlebot' => 'Googlebot',
        'Sogou web spider' => 'Sogou web spider/VER',
        'Sogou inst spider' => 'Sogou inst spider/VER',
        '360Spider' => '360Spider',
        'HaoSouSpider' => 'HaoSouSpider',
        
        'MicroMessenger' => 'MicroMessenger/VER', // Tencent Wechat(MicroMessenger)
        'MQQBrowser' => 'MQQBrowser/VER', // Tencent QQBrowser mobile
        'QQ-MttCustomUA' => 'MttCustomUA/VER', // Tencent QQBrowser mobile CustomUA
        'QQ' => 'QQ/VER', // Tencent QQ

        'AlipayClient' => 'AlipayClient/VER',  // Alipay App
        'BaiduApp' => 'baiduboxapp/VER',  // Baidu App
        'baidubrowser' => 'baidubrowser/VER',
        'Chrome CriOS' => 'CriOS/VER', // Chrome mobile
        'Firefox FxiOS' => 'FxiOS/VER', // Firefox mobile
        'Opera OPiOS' => 'OPiOS/VER', // Opera mobile
        'Opera' => 'OPR/VER',
        'UCQuark' => 'Quark/VER', // UCBrowser Quark
        'UCBrowser' => 'UCBrowser/VER',  // UCBrowser
        'UCWEB' => 'UCWEB/VER',
        'LieBaoFast' => 'LieBaoFast/VER', // Libebao
        'MiuiBrowser' => 'MiuiBrowser/VER', // MiuiBrowser

        'QihooBrowser' => 'QihooBrowser/VER', // 360Browser
        'QHBrowser' => 'QHBrowser/VER', // 360Browser

        'DolphinBrowserCN' => 'DolphinBrowserCN/VER', // DolphinBrowserCN
        'SogouMobileBrowser' => 'SogouMobileBrowser/VER', // SogouBrowser mobile
        'MiniBrowser' => 'MiniBrowser/VER', // MiniBrowser mobile
        'Mb2345Browser' => 'Mb2345Browser/VER', // 2345Browser

        'MaxthonMXiOS' => 'MXiOS/VER',
        'Maxthon' => 'Maxthon/VER',
        'Edge' => 'Edge/VER',
        'YaBrowser' => 'YaBrowser/VER', // Yandex Browser (Yowser)
        'Vivaldi' => 'Vivaldi/VER',
        'Chrome' => 'Chrome/VER',
        'Firefox' => 'Firefox/VER',
        'Safari' => 'Safari/VER',
        // MSIE 8.0;
        'IE6' => 'MSIE\ (6.\w+);',
        'IE7' => 'MSIE\ (7.\w+);',
        'IE8' => 'MSIE\ (8.\w+);',
        'IE9' => 'MSIE\ (9.\w+);',
        'IE10' => 'MSIE\ (10.\w+);',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Bolt' => 'bolt/VER',
        'TeaShark' => 'teashark/VER',
        'Blazer' => 'Blazer/VER',
        'Mozilla' => 'Mozilla/VER',
    ];

    /**
     * @return array
     */
    public function getSystem()
    {
        if ($this->isWindows()) {
            $rules = $this->windowsSystemRules;
        } elseif ($this->isMacintosh()) {
            $rules = $this->macintoshSystemRules;
        } else {
            $rules = $this->defaultSystemRules;
        }

        return $this->system = $this->phraseResult($rules);
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        if (empty($this->system)) {
            $this->getSystem();
        }

        return !empty($this->system['name']) ? $this->system['name'] : 'unknown';
    }

    /**
     * @return string
     */
    public function getSystemVersion()
    {
        if (empty($this->system)) {
            $this->getSystem();
        }

        return !empty($this->system['version']) ? $this->system['version'] : 'unknown';
    }

    /**
     * @return array
     */
    public function getDevice()
    {
        $rules = $this->deviceRules;
        if ($this->isAndroidOS()) {
            $rules = $this->androidDeviceRules;
        }

        return $this->device = $this->phraseResult($rules);
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        if (empty($this->device)) {
            $this->getDevice();
        }

        return !empty($this->device['name']) ? $this->device['name'] : 'unknown';
    }

    /**
     * @return string
     */
    public function getDeviceVersion()
    {
        if (empty($this->device)) {
            $this->getDevice();
        }

        return !empty($this->device['version']) ? $this->device['version'] : 'unknown';
    }

    /**
     * @return array
     */
    public function getBrowser()
    {
        $rules = $this->browserRules;

        foreach ($rules as $key => $regex) {
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


    /**
     * @return string
     */
    public function getBrowserName()
    {
        if (empty($this->browser)) {
            $this->getBrowser();
        }

        return !empty($this->browser['name']) ? $this->browser['name'] : 'unknown';
    }

    /**
     * @return string
     */
    public function getBrowserVersion()
    {
        if (empty($this->browser)) {
            $this->getBrowser();
        }

        return !empty($this->browser['version']) ? $this->browser['version'] : '';
    }

    /**
     * @return bool
     */
    public function isIOS()
    {
        return stripos($this->userAgent, 'iPhone') !== false;
    }

    /**
     * @return bool
     */
    public function isAndroidOS()
    {
        return stripos($this->userAgent, 'Android') !== false;
    }

    /**
     * @return bool
     */
    public function isMacintosh()
    {
        return stripos($this->userAgent, 'Macintosh') !== false;
    }

    /**
     * @return bool
     */
    public function isWindows()
    {
        return stripos($this->userAgent, 'Windows') !== false;
    }

    /**
     * @return bool
     */
    public function isMSIE()
    {
        return stripos($this->userAgent, 'MSIE') !== false;
    }

    /**
     * @return bool
     */
    public function isWechatBrowser()
    {
        return stripos($this->userAgent, 'MicroMessenger') !== false;
    }

    /**
     * @return bool
     */
    public function getWechatVersion()
    {
        if ($this->pregMatch('MicroMessenger/' . $this->versionRegex, $this->userAgent)) {
            return $this->matchArray[1] ?? '';
        }

        return false;
    }

    /**
     * @return bool|string
     */
    public function getNetType()
    {
        $netType = 'Unknown';
        if (stripos($this->userAgent, 'netType') !== false) {
            if ($this->pregMatch('netType/(\S+)', $this->userAgent)) {
                return $this->matchArray[1] ?? 'unknown';
            }
        }

        return $netType;
    }

}
