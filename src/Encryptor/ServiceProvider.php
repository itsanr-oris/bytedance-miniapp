<?php


namespace EasyByteDance\MiniApp\Encryptor;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register encryptor service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['encryptor']) && $app['encryptor'] = function ($app) {
            return new Encryptor($app);
        };
    }
}
