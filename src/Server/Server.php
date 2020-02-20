<?php

namespace EasyByteDance\MiniApp\Server;

use EasyByteDance\MiniApp\Component;

/**
 * Class Sign
 */
class Server extends Component
{
    /**
     * 服务端数据签名
     *
     * @param       $data
     * @param array $expectKeys
     * @return string
     */
    public function signature($data, $expectKeys = [])
    {
        ksort($data);

        foreach ($data as $key => $value) {
            if (empty($value) || $key == 'sign' || $key == 'risk_info' || in_array($key, $expectKeys)) {
                unset($data[$key]);
            }

            if (!is_string($value)) {
                $data[$key] = json_encode($value);
            }
        }

        return md5(urldecode(http_build_query($data)) . $this->app()->config['app_secret']);
    }
}
