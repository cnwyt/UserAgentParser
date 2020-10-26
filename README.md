*UserAgentParser*

A Simple PHP User Agent Parser

### Install

Packagist [cnwyt/user-agent-parser](https://packagist.org/packages/cnwyt/user-agent-parser)

```sh
$ composer require cnwyt/user-agent-parser
```

### Usage

```php
use Cnwyt\UserAgentParser\UserAgentParser;


// 获取 UserAgent
// $ua = $request->get('ua');
$ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0';

// 实例化 UserAgentParser
$agent = (new UserAgentParser());

// 设置 UserAgent
$agent->setUserAgent($ua);

// 获取解析结果
$device = $agent->getDeviceName();

$browerName = $agent->getBrowserName();
$browerVersion = $agent->getBrowserVersion();

$systemName = $agent->getSystemName();
$systemVersion = $agent->getSystemVersion();


// 检测
$isIos = $agent->isIOS();

$isAndroid = $agent->isAndroidOS();

$isWechat =  $agent->isWechatBrowser();
```

### market report

NetMarketShare (2020-09): 

```
Chrome     69.94%
Edge       8.84%
Firefox    7.19%
InternetExplorer 3.88%
Safari     3.57%
QQ         2.35%
SogouExplorer 1.48%
Opera      0.97%
Yandex     0.79%
UC Browser 0.52%
```

Baidu report (2020): 

Chrome > UC > 微信(wechat) > IE > QQ > 百度(baidu) > Safari > 其他(others)

### Tests

```sh
$ git clone https://github.com/cnwyt/UserAgentParser.git
$ cd UserAgentParser
$ composer install
$ ./vendor/bin/phpunit tests/
```
