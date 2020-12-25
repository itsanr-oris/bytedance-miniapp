<?php

namespace EasyByteDance\MiniApp\Payment;

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
        !isset($app['tt_pay']) && $app['tt_pay'] = function ($app) {
            return new TtPay($app);
        };
    }
}
