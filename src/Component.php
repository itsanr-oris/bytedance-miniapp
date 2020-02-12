<?php

namespace EasyByteDance\MiniApp;

use Foris\Easy\Sdk\ServiceContainer;
use EasyByteDance\MiniApp\Http\HttpClient;

/**
 * Class Component
 */
class Component extends \Foris\Easy\Sdk\Component
{
    /**
     * 获取应用程序实例
     *
     * @return ServiceContainer|Application
     */
    public function app(): ServiceContainer
    {
        return parent::app();
    }

    /**
     * 获取http client实例
     *
     * @return \Foris\Easy\HttpClient\HttpClient|HttpClient
     */
    public function http()
    {
        return parent::http();
    }
}
