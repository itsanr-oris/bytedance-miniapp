## EasyByteDance/MiniApp

字节跳动系小程序SDK，参照[easy-wechat](https://github.com/overtrue/wechat)实现

[![Build Status](https://travis-ci.com/itsanr-oris/bytedance-miniapp.svg?branch=master)](https://travis-ci.com/itsanr-oris/bytedance-miniapp)
[![codecov](https://codecov.io/gh/itsanr-oris/bytedance-miniapp/branch/master/graph/badge.svg)](https://codecov.io/gh/itsanr-oris/bytedance-miniapp)
[![Latest Stable Version](https://poser.pugx.org/f-oris/easy-bytedance-miniapp/v/stable)](https://packagist.org/packages/f-oris/easy-bytedance-miniapp)
[![Latest Unstable Version](https://poser.pugx.org/f-oris/easy-bytedance-miniapp/v/unstable)](https://packagist.org/packages/f-oris/easy-bytedance-miniapp)
[![Total Downloads](https://poser.pugx.org/f-oris/easy-bytedance-miniapp/downloads)](https://packagist.org/packages/f-oris/easy-bytedance-miniapp)
[![License](https://poser.pugx.org/f-oris/easy-bytedance-miniapp/license)](https://packagist.org/packages/f-oris/easy-bytedance-miniapp)

## 功能

- [x] 小程序登录
- [x] 授权信息解密
- [x] 发送模板消息
- [x] 获取小程序二维码
- [x] 设置数据缓存
- [x] 删除数据缓存
- [x] 内容安全检查
- [x] 服务端数据签名
- [x] 小程序收银台服务端签名

## 安装

```bash
composer require jzweb/bytedance-miniapp
```

## 基本使用

参考[easy-wechat](https://github.com/overtrue/wechat)使用文档，因为是仿着做的，所以小程序各组件提供的方法，含义，用法基本上和easy-wechat一致

## 获取小程序二维码

```php
// 配置好config...

$app = new Application($config);
$code = $app->app_code->get();

$file = fopen(__DIR__ . '/code.png', 'w+');
fwrite($file, $code);
fclose($file);

```

## 设置数据缓存

```php
// 配置好config，获取登录用户openid, session_key

$app = new Application($config);

$openId = 'openid';
$sessionKey = 'session_key';
$kvList = [
    ['key' => 'custom-key', 'value' => 'custom-value']
];

$app->user_storage->set($openId, $sessionKey, $kvList);

```

## 删除数据缓存

```php
// 配置好config，获取登录用户openid, session_key

$app = new Application($config);

$openId = 'openid';
$sessionKey = 'session_key';
$keys = ['custom_key'];

$app->user_storage->remove($openId, $sessionKey, $keys);

```

## 服务端数据签名

```php
// 配置好config，以下测试默认app_secret的值为字符串"a"

$app = new Application($config);

$data = [
    'app_id' => '800000000001',
    'merchant_id' => '1900000001',
    'timestamp' => 1570694312,
    'sign_type' => 'MD5',
    'out_order_no' => '201900000000000001',
    'total_amount' => 1,
    'product_code' => 'pay',
    'payment_type' => 'direct',
    'trade_type' => 'H5',
    'version' => '2.0',
    'currency' => 'CNY',
    'subject' => '测试订单',
    'body' => '测试订单',
    'uid' => '0000000000000001',
    'trade_time' => 1570585744,
    'valid_time' => 300,
    'notify_url' => '',
    'risk_info' => '{"ip":"120.230.0.0"}',
    'wx_type' => 'MWEB',
    'wx_url' => 'https://wx.tenpay.com/xxx',
    'alipay_url' => 'app_id=2019000000000006&biz_content=xxxx'
];

$app->server->signature($data);
// 0f1e3358a9898d7c4c6c23740251808a

```

## 小程序收银台服务端签名

```php
// 配置好config，注意增加了一个配置项payment_secert，以下测试默认该配置项的值为字符串"a"

$app = new Application($config);

$data = [
    "app_id" => "800000040005",
    "sign_type" => "MD5",
    "out_order_no" => "MicroApp7075638135",
    "merchant_id" => "1300000004",
    "timestamp" => "1566720681",
    "product_code" => "pay",
    "payment_type" => "direct",
    "total_amount" => 1,
    "trade_type" => "H5",
    "uid" => "2019012211",
    "version" => "2.0",
    "currency" => "CNY",
    "subject" => "microapp test",
    "body" => "microapp test",
    "trade_time" => "1566720681",
    "valid_time" => "300",
    "notify_url" => "https://tp-pay.snssdk.com/cashdesk/test/paycallback",
    "wx_url" => "https://wx.tenpay.com/cgi-bin/mmpayweb-bin/checkmweb?prepay_id=wx25161122572189727ea14cfd1832451500&package=2746219290",
    "wx_type" => "MWEB",
    "alipay_url" => "alipay_sdk=alipay-sdk-java-3.4.27.ALL&app_id=2018061460417275&biz_content=%7B%22body%22%3A%22%E6%B5%8B%E8%AF%95%E8%AE%A2%E5%8D%95%22%2C%22extend_params%22%3A%7B%7D%2C%22out_trade_no%22%3A%2211908250000028453790%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22seller_id%22%3A%222088721387102560%22%2C%22subject%22%3A%22%E6%B5%8B%E8%AF%95%E8%AE%A2%E5%8D%95%22%2C%22timeout_express%22%3A%22599m%22%2C%22total_amount%22%3A%220.01%22%7D&charset=utf-8&format=json&method=alipay.trade.app.pay&notify_url=http%3A%2F%2Fapi-test-pcs.snssdk.com%2Fgateway%2Fpayment%2Fcallback%2Falipay%2Fnotify%2Fpay&sign=D2A6ua51os2aIzIH907ppK7Bd9Q2Kk5h7AtKPdudP%2Be%2BNTtAkp0Lfojtgl4BMOIQ3Z7cWyYMx6nk4qbntSx7aZnBhWAcImLbVVr1cmaYAedmrmJG%2B3f8G5TfAZu53ESzUgk02%2FhU1XV0iXRyE8TdEJ97ufmxwsUEc7K0EvwEFDIBCJg73meQtyCRFgCqYRWvmxetQgL7pwfKXpFXjAYsvFrRBas2YGYt689XpBS321g%2BZ8SZ0JOtLPWqhROzEs3dnAtWBW15y3NzRiSNi5rPzah4cWd4SgT0LZHmNf3eDQEHEcPmofoWfnA4ao75JmP95aLUxerMumzo9OwqhiYOUw%3D%3D&sign_type=RSA2&timestamp=2019-08-25+16%3A11%3A22&version=1.0",
    "risk_info" => "{\"ip\":\"127.0.0.1\"}"
];

$app->tt_pay->signature($data);
// 07d5988aa5dc9f9f604711a118ad16cf

```

## License

MIT License

Copyright (c) 2019-present F.oris <us@f-oris.me>
