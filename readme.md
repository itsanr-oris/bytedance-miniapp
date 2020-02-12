## EasyToutiao/MiniProgram

字节跳动系小程序SDK，参照[easy-wechat](https://github.com/overtrue/wechat)实现

## 功能

- [x] 小程序登录
- [x] 授权信息解密
- [ ] 发送模板消息
- [x] 获取小程序二维码
- [x] 设置数据缓存
- [x] 删除数据缓存
- [x] 内容安全检查

模板消息由于开发环境下测试发送返回40039，form_id过期状态码，暂时没有在实际的应用测试通过

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

app->user_storage->set($openId, $sessionKey, $kvList);

```

## 删除数据缓存

```php
// 配置好config，获取登录用户openid, session_key

$app = new Application($config);

$openId = 'openid';
$sessionKey = 'session_key';
$keys = ['custom_key'];

app->user_storage->remove($openId, $sessionKey, $keys);

```

## License

MIT License

Copyright (c) 2019-present F.oris <us@f-oris.me>
