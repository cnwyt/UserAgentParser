<?php

namespace Cnwyt\UserAgentParser;

class UserAgentParser implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $version = '0.5.0';

    protected $userAgent = '';
    protected $md5 = '';
    protected $browser = [];
    protected $system = [];
    protected $device = [];
    protected $netType = '';

    protected $matchPattern = null;
    protected $matchArray = [];

    /**
     * @param string $userAgent
     */
    public function __construct($userAgent = '')
    {
        $this->userAgent = $userAgent;
        $this->md5 = md5($userAgent);
    }

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
     * @return array
     */
    public function result()
    {
        return [
            'browser' => $this->getBrowser(),
            'system' => $this->getSystem(),
            'device' => $this->getDevice(),
            'netType' => $this->getNetType(),
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
        foreach ($rules as $regex => $name) {
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $result = [
                    'name' => $name,
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
     * @param string $userAgent
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
     * @var string
     */
    protected $versionRegex = '([\w._\+]+)';

    /**
     * @var array
     */
    protected $deviceRules = [
        // mobile phone
        '\biPhone\b|\biPod\b' => 'iPhone',
        // tablet
        'iPad|iPad.*Mobile' => 'iPad',
        'Intel Mac OS X' => 'MacOSX(Intel)',
        'Macintosh' => 'Macintosh',
        'Windows' => 'Windows',
        'Linux' => 'Linux',
    ];

    /**
     * @var array
     */
    protected $androidDeviceRules = [
        'HUAWEISTF' => 'Huawei',
        'HUAWEI' => 'HUAWEI',
        'OPPO R11st' => 'OPPO R11st',
        'OPPO R11' => 'OPPO R11',
        'OPPO' => 'OPPO',
        'vivo NEX' => 'vivo NEX',
        'vivo' => 'vivo',
        'MI NOTE' => 'MI NOTE',
        'MIX' => 'MIX',
        'MX4 Pro' => 'MX4 Pro',
        'Redmi Note' => 'Redmi Note',
        'Redmi' => 'Redmi',
        'SM-G900P' => 'SamSung-G900P',
        'SM-N900T' => 'SamSung-N900T',
        'SM-G95' => 'SamSung-Galaxy-S8',
        'SM-' => 'SamSung',
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
        'Android\ ([\w.+]+);' => 'Android',
        'Android' => 'AndroidOS',
        // (iPhone; CPU iPhone OS 10_3_1 like Mac OS X)
        'iPhone OS (\w+_\w+_\w+)' => 'iPhone',
        // (iPad; CPU OS 11_0 like Mac OS X)
        // (iPad; CPU OS 11_0 like Mac OS X)
        'iPad; CPU OS (\w+_\w+) like' => 'iPad',
        '\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia' => 'iPhone',

        // 'Mac OS X' => 'Mac OS X',
        // (Macintosh; Intel Mac OS X 10_13_6)
        'Mac\ OS\ X\ (\w+_\w+_\w+)' => 'Mac',
        'Mac OS X' => 'Mac',
        'Macintosh' => 'Mac',
        // linux
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'CrOS' => 'ChromeOS',
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
        'Windows NT (5.0)' => 'Windows2000',
        'Windows NT (5.1)|Windows NT (5.2)' => 'WindowsXP',
        'Windows NT (6.0)' => 'WindowsVista',
        'Windows NT (6.1)' => 'Windows7',
        'Windows NT (6.2)|Windows NT (6.3)' => 'Windows8',
        'Windows NT (6.4)|Windows NT (10.0)' => 'Windows10',
        'Windows Phone' => 'Windows Phone',
        'Windows NT' => 'Windows NT',
        'Windows' => 'Windows',
    ];

    /**
     * @var array
     */
    protected $macSystemRules = [
        // (Macintosh; Intel Mac OS X 10_13_6)
        'Mac\ OS\ X\ (\w+_\w+_\w+)' => 'Mac OS X',
        'Mac\ OS\ X\ (\w+.\w+)' => 'Mac OS X', // Mac OS X 10.15
        'Mac OS X' => 'Mac OS X',
        'Macintosh' => 'Macintosh',
    ];

    /**
     * @var array
     */
    protected $browserRules = [
        'Baiduspider' => 'Baiduspider',
        'Bingbot' => 'Bingbot',
        'Googlebot' => 'Googlebot',
        'Sogou web spider/VER' => 'Sogou web spider',
        'Sogou inst spider/VER' => 'Sogou inst spider',
        '360Spider' => '360Spider',
        'HaoSouSpider' => 'HaoSouSpider',
        'MicroMessenger/VER' => 'MicroMessenger',
        'MQQBrowser/VER' => 'MQQBrowser',
        'MttCustomUA/VER' => 'QQ-MttCustomUA',
        'QQ/VER' => 'QQ',
        'AlipayClient/VER' => 'AlipayClient',
        'baiduboxapp/VER' => 'BaiduApp',
        'baidubrowser/VER' => 'baidubrowser',
        'CriOS/VER' => 'Chrome CriOS',
        'FxiOS/VER' => 'Firefox FxiOS',
        'OPiOS/VER' => 'Opera OPiOS',
        'OPR/VER' => 'Opera',
        'Opera/VER' => 'Opera',
        'Quark/VER' => 'UCQuark',
        'UCBrowser/VER' => 'UCBrowser',
        'UCWEB/VER' => 'UCWEB',
        'LieBaoFast/VER' => 'LieBaoFast',
        'SamsungBrowser/VER' => 'SamsungBrowser',
        'MiuiBrowser/VER' => 'MiuiBrowser',
        'OppoBrowser/VER' => 'OppoBrowser',
        'VivoBrowser/VER' => 'VivoBrowser',
        'QihooBrowser/VER' => 'QihooBrowser',
        'QHBrowser/VER' => 'QHBrowser',
        'DolphinBrowserCN/VER' => 'DolphinBrowserCN',
        'SogouMobileBrowser/VER' => 'SogouMobileBrowser',
        'MiniBrowser/VER' => 'MiniBrowser',
        'Mb2345Browser/VER' => 'Mb2345Browser',
        'MXiOS/VER' => 'MaxthonMXiOS',
        'Maxthon/VER' => 'Maxthon',
        'Edge/VER' => 'Edge',
        'YaBrowser/VER' => 'YaBrowser',
        'Vivaldi/VER' => 'Vivaldi',
        'Chrome/VER' => 'Chrome',
        'Firefox/VER' => 'Firefox',
        'Safari/VER' => 'Safari',
        'MSIE\ (6.\w+);' => 'IE6',
        'MSIE\ (7.\w+);' => 'IE7',
        'MSIE\ (8.\w+);' => 'IE8',
        'MSIE\ (9.\w+);' => 'IE9',
        'MSIE\ (10.\w+);' => 'IE10',
        'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+' => 'IE',
        'bolt/VER' => 'Bolt',
        'teashark/VER' => 'TeaShark',
        'Blazer/VER' => 'Blazer',
        'Mozilla/VER' => 'Mozilla',
    ];

    public function getOSName(): string
    {
        $agent = $this->userAgent;

        if (preg_match('/(Spider|Crawl|Bot)/i', $agent)) {
            $os = 'Spider';
        } elseif (strpos($agent, 'Windows')) {
            $os = 'Windows';
        } elseif (strpos($agent, 'Macintosh')) {
            $os = 'Macintosh';
        } elseif (strpos($agent, 'Android')) {
            $os = 'Android';
        } elseif (strpos($agent, 'iPhone')) {
            $os = 'iPhone';
        } elseif (strpos($agent, 'iPad')) {
            $os = 'iPad';
        } elseif (strpos($agent, 'Linux')) {
            $os = 'Linux';
        } elseif (strpos($agent, 'FreeBSD')) {
            $os = 'FreeBSD';
        } elseif (strpos($agent, 'SunOS')) {
            $os = 'SunOS';
        } elseif (strpos($agent, 'OS/2')) {
            $os = 'OS/2';
        } elseif (strpos($agent, 'AIX')) {
            $os = 'AIX';
        } else {
            $os = 'others';
        }

        return $os;
    }

    /**
     * @return array
     */
    public function getSystem()
    {
        if ($this->isWindows()) {
            $rules = $this->windowsSystemRules;
        } elseif ($this->isMacintosh()) {
            $rules = $this->macSystemRules;
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
        if (empty($this->device) || $this->md5 != md5($this->userAgent)) {
            $this->getDevice();
        }

        return !empty($this->device['name']) ? $this->device['name'] : 'unknown';
    }

    /**
     * @return string
     */
    public function getDeviceVersion()
    {
        if (empty($this->device) || $this->md5 != md5($this->userAgent)) {
            $this->getDevice();
        }

        return !empty($this->device['version']) ? $this->device['version'] : 'unknown';
    }

    /**
     * @return array
     */
    public function getBrowser()
    {
        foreach ($this->browserRules as $regex => $name) {
            $regex = str_ireplace('VER', $this->versionRegex, $regex);
            if ($regex && $this->pregMatch($regex, $this->userAgent)) {
                $this->browser = [
                    'name' => $name,
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
        if (empty($this->browser) || $this->md5 != md5($this->userAgent)) {
            $this->getBrowser();
        }

        return !empty($this->browser['name']) ? $this->browser['name'] : 'unknown';
    }

    /**
     * @return string
     */
    public function getBrowserVersion()
    {
        if (empty($this->browser) || $this->md5 != md5($this->userAgent)) {
            $this->getBrowser();
        }

        return !empty($this->browser['version']) ? $this->browser['version'] : '';
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->isIOS() || $this->isAndroidOS();
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
    public function isMacintosh(): bool
    {
        return stripos($this->userAgent, 'Macintosh') !== false;
    }

    /**
     * @return bool
     */
    public function isWindows(): bool
    {
        return stripos($this->userAgent, 'Windows') !== false;
    }

    /**
     * @return bool
     */
    public function isLinux(): bool
    {
        return stripos($this->userAgent, 'Linux') !== false;
    }

    /**
     * @return bool
     */
    public function isRobot(): bool
    {
        return preg_match('/(Crawl|Spider|Bot)/i', $this->userAgent) ? true : false;
    }

    /**
     * @return bool
     */
    public function isSprider(): bool
    {
        return $this->isRobot();
    }

    /**
     * @return bool
     */
    public function isMSIE(): bool
    {
        return stripos($this->userAgent, 'MSIE') !== false;
    }

    /**
     * @return bool
     */
    public function isWechatBrowser(): bool
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
        $netType = 'unknown';
        // wechat: "NetType/WIFI"
        if (stripos($this->userAgent, 'NetType') !== false) {
            if ($this->pregMatch('NetType/(\S+)', $this->userAgent)) {
                $netType = $this->matchArray[1] ?? 'unknown';
            }
        }
        // aliApp: "AlipayDefined(nt:WIFI,ws:414|672|3.0)"
        if (stripos($this->userAgent, 'nt:') !== false) {
            if ($this->pregMatch('\(nt:(\S+),', $this->userAgent)) {
                $netType = $this->matchArray[1] ?? 'unknown';
            }
        }

        return $netType;
    }

}
