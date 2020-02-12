<?php

namespace EasyByteDance\MiniApp\ContentSecurity;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register content security service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['content_security']) && $app['content_security'] = function ($app) {
            return new ContentSecurity($app);
        };
    }
}

