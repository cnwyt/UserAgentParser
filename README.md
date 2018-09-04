README.md


[cnwyt/user-agent-parser](https://packagist.org/packages/cnwyt/user-agent-parser)


### Install

```
composer require cnwyt/user-agent-parser
```

### Usage

```
use Cnwyt\UserAgentParser\UserAgentParser;


// 获取 UserAgent
$ua = $request->get('ua');

// 实例化 UserAgentParser
$agent = (new UserAgentParser());

// 设置 UserAgent
$agent->setUserAgent($ua);

// 获取解析结果

$device = $agent->getDeviceName();

$browerName = $agent->getBrowserName();
$browerVersion = $agent->getBrowserVersion();

$systemName = $agent->getSystemName();


// 检测
$isIos = $agent->isIOS();

$isAndroid = $agent->isAndroidOS();

$isWechat =  $agent->isWechatBrowser();
```

