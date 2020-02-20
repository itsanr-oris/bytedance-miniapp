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

## 安装

```bash
composer require f-oris/easy-bytedance-miniapp
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
// 配置好config

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

## License

MIT License

Copyright (c) 2019-present F.oris <us@f-oris.me>
