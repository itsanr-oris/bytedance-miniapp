<?php

namespace EasyByteDance\MiniApp\Http;

use Pimple\Container;
use Foris\Easy\Sdk\Providers\HttpClientProvider;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends HttpClientProvider
{
    /**
     * Register http client service component
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['http_client']) && $app['http_client'] = function ($app) {
            $client = new HttpClient($app);

            $this->addLogMiddleware($app, $client);
            $this->addRetryMiddleware($app, $client);

            return $client;
        };
    }
}
