<?php

namespace EasyByteDance\MiniApp\Tests\Payment;

use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class TtPayTest
 */
class TtPayTest extends TestCase
{
    /**
     * Test generate payment signature.
     */
    public function testGeneratePaymentSignature()
    {
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

        $signature = '07d5988aa5dc9f9f604711a118ad16cf';
        $this->app()->config['payment_secret'] = 'a';
        $this->assertEquals($signature, $this->app()->tt_pay->signature($data));
    }
}
