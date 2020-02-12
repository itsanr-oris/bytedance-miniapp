<?php

namespace EasyByteDance\MiniApp\TemplateMessage;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register template message service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['template_message']) && $app['template_message'] = function ($app) {
            return new TemplateMessage($app);
        };
    }
}
