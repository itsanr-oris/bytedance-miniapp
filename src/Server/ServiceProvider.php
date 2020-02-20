<?php

namespace EasyByteDance\MiniApp\Server;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register http client service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['server']) && $app['server'] = function ($app) {
            return new Server($app);
        };
    }
}
