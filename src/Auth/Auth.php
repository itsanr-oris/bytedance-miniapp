<?php

namespace EasyByteDance\MiniApp\Auth;

use EasyByteDance\MiniApp\Component;

/**
 * Class Auth
 */
class Auth extends Component
{
    /**
     * @var string
     */
    protected $endPoint = 'jscode2session';

    /**
     * 认证
     *
     * @param      $code
     * @param null $anonymousCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function session($code, $anonymousCode = null)
    {
        $params = [
            'code' => $code,
            'anonymous_code' => $anonymousCode,
            'appid' => $this->app['config']['app_id'],
            'secret' => $this->app['config']['app_secret'],
        ];

        return $this->http()->withAccessToken(false)->get($this->endPoint, $params);
    }
}
