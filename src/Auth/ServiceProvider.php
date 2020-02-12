<?php

namespace EasyByteDance\MiniApp\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * 注册认证组件实例
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['auth']) && $app['auth'] = function ($app) {
            return new Auth($app);
        };

        !isset($app['access_token']) && $app['access_token'] = function ($app) {
            return new AccessToken($app);
        };
    }
}
