<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Http;

use EasyByteDance\MiniApp\Application;
use Foris\Easy\HttpClient\ResponseHandler;

/**
 * Class HttpClient
 */
class HttpClient extends \Foris\Easy\HttpClient\HttpClient
{
    /**
     * 小程序实例
     *
     * @var Application
     */
    protected $app;

    /**
     * 请求是否携带access_token
     *
     * @var bool
     */
    protected $withAccessToken = true;

    /**
     * 请求响应数据格式
     *
     * @var string
     */
    protected $responseType = ResponseHandler::TYPE_COLLECTION;

    /**
     * HttpClient constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct($app->config['http_client'] ?? []);
    }

    /**
     * 设置请求是否携带access_token
     *
     * @param bool $withAccessToken
     * @return $this
     */
    public function withAccessToken($withAccessToken = true)
    {
        $this->withAccessToken = $withAccessToken;
        return $this;
    }

    /**
     * 执行请求
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     * @return mixed
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function request(string $url, $method = 'GET', $options = [])
    {
        if ($this->withAccessToken && empty($options['query']['access_token'])) {
            $token = $this->app->access_token->getAccessToken();
            $options['query']['access_token'] = $options['headers']['X-Token'] = $token;
        }

        return $this->handleResponse(parent::request($url, $method, $options));
    }

    /**
     * 处理请求结果
     *
     * @param $response
     * @return mixed
     */
    protected function handleResponse($response)
    {
        // reset next request with access token
        $this->withAccessToken();

        return $response;
    }
}

