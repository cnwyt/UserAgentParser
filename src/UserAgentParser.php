<?php

namespace Cnwyt\UserAgentParser;

class UserAgentParser implements \JsonSerializable
{
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
        // tablet
        'iPad' => 'iPad|iPad.*Mobile',

        'Mac' => 'Macintosh',
    ];

    protected $systemRules = [
        'AndroidOS' => 'Android',
        'iOS' => '\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia',
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        'MacOSX' => 'Mac OS X',
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];

    protected $browserRules = [
        'MQQBrowser' => 'MQQBrowser/VER',
        'Opera' => 'OPR/(VER)',
        'UCBrowser' => 'UCBrowser',
        'UCWEB' => 'UCWEB',
        'Edge' => 'Edge',
        'Vivaldi' => 'Vivaldi',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Bolt' => 'bolt',
        'TeaShark' => 'teashark',
        'Blazer' => 'Blazer',
        'baiduboxapp' => 'baiduboxapp',
        'baidubrowser' => 'baidubrowser',
        'Mozilla' => 'Mozilla',
    ];

    public function getSystem()
    {
        foreach ($this->systemRules as $key => $regex) {
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
