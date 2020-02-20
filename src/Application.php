<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace EasyByteDance\MiniApp;

use Foris\Easy\Sdk\ServiceContainer;
use Foris\Easy\Sdk\Providers\ConfigProvider;
use Foris\Easy\Sdk\Providers\CacheProvider;
use Foris\Easy\Sdk\Providers\LoggerProvider;
use EasyByteDance\MiniApp\Http\ServiceProvider as HttpClientProvider;

/**
 * Class Application
 *
 * @property \EasyByteDance\MiniApp\Auth\Auth                       $auth
 * @property \EasyByteDance\MiniApp\Auth\AccessToken                $access_token
 * @property \EasyByteDance\MiniApp\AppCode\AppCode                 $app_code
 * @property \EasyByteDance\MiniApp\Encryptor\Encryptor             $encryptor
 * @property \EasyByteDance\MiniApp\TemplateMessage\TemplateMessage $template_message
 * @property \EasyByteDance\MiniApp\ContentSecurity\ContentSecurity $content_security
 * @property \EasyByteDance\MiniApp\UserStorage\UserStorage         $user_storage
 * @property \EasyByteDance\MiniApp\Server\Server                   $server
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $defaultProviders = [
        ConfigProvider::class,
        CacheProvider::class,
        LoggerProvider::class,
        HttpClientProvider::class,
    ];

    /**
     * 启用的组件
     *
     * @var array
     */
    protected $providers = [
        \EasyByteDance\MiniApp\Auth\ServiceProvider::class,
        \EasyByteDance\MiniApp\Encryptor\ServiceProvider::class,
        \EasyByteDance\MiniApp\AppCode\ServiceProvider::class,
        \EasyByteDance\MiniApp\TemplateMessage\ServiceProvider::class,
        \EasyByteDance\MiniApp\ContentSecurity\ServiceProvider::class,
        \EasyByteDance\MiniApp\UserStorage\ServiceProvider::class,
        \EasyByteDance\MiniApp\Server\ServiceProvider::class,
    ];

    /**
     * 重写配置信息
     *
     * @return array
     */
    public function getConfig()
    {
        $this->userConfig['http_client']['timeout'] = $this->userConfig['http_client']['timeout'] ?? 30;
        $this->userConfig['http_client']['base_uri'] = 'https://developer.toutiao.com/api/apps/';

        return $this->userConfig;
    }
}
