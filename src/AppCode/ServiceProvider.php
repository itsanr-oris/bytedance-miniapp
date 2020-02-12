<?php

namespace EasyByteDance\MiniApp\AppCode;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register qr code service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['app_code']) && $app['app_code'] = function ($app) {
            return new AppCode($app);
        };
    }
}
