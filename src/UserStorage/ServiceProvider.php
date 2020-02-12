<?php


namespace EasyByteDance\MiniApp\UserStorage;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register user storage service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['user_storage']) && $app['user_storage'] = function ($app) {
            return new UserStorage($app);
        };
    }
}
