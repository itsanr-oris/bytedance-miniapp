<?php

namespace EasyByteDance\MiniApp\Payment;

use EasyByteDance\MiniApp\Component;

/**
 * Class Signature
 */
class TtPay extends Component
{
    /**
     * 生成支付签名
     *
     * @param array $data
     * @return string
     */
    public function signature($data = [])
    {
        $data = array_filter($data, function ($value, $key) {
            return !in_array($key, ['sign', 'risk_info']) && !empty($value);
        }, ARRAY_FILTER_USE_BOTH);

        ksort($data);

        return md5(urldecode(http_build_query($data)). $this->app()->config['payment_secret']);
    }
}
