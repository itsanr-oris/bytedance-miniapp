<?php

namespace EasyByteDance\MiniApp\Tests\Server;

use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class ServerTest
 */
class ServerTest extends TestCase
{
    /**
     * 测试服务端数据签名
     */
    public function testSignature()
    {
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

        $signature = '0f1e3358a9898d7c4c6c23740251808a';
        $this->app()->config['app_secret'] = 'a';
        $this->assertEquals($signature, $this->app()->server->signature($data));
    }
}
