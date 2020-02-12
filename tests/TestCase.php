<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests;

use EasyByteDance\MiniApp\Http\HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use EasyByteDance\MiniApp\Application;

/**
 * Class TestCase
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * 小程序实例
     *
     * @var Application
     */
    protected $app;

    /**
     * GuzzleHttp测试handler
     *
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * 历史请求
     *
     * @var array
     */
    protected $historyRequest = [];

    /**
     * set up test environment
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function setUp(): void
    {
        $this->historyRequest = [];
        $this->mockHandler = new MockHandler();
        $this->app = new Application(require __DIR__ . '/../config.example.php');

        $handlerStack = $this->app->http_client->getHandlerStack();
        $handlerStack->setHandler($this->mockHandler);
        $handlerStack->push(Middleware::history($this->historyRequest));
        $this->app->http_client->setHandlerStack($handlerStack);

        $this->setUpAccessToken();
    }

    /**
     * 小程序实例
     *
     * @return Application
     */
    protected function app()
    {
        return $this->app;
    }

    /**
     * Get http instance
     *
     * @return \Foris\Easy\HttpClient\HttpClient|HttpClient
     */
    protected function http()
    {
        return $this->app()->http_client;
    }

    /**
     * Add response
     *
     * @param int $code
     * @param array $headers
     * @param string $body
     * @param string $version
     * @param string|null $reason
     * @return $this
     */
    protected function appendResponse(
        int $code = 200,
        array $headers = [],
        string $body = '',
        string $version = '1.1',
        string $reason = null
    ) {
        $this->mockHandler->append(new Response($code, $headers, $body, $version, $reason));
        return $this;
    }

    /**
     * 获取历史请求记录
     *
     * @return array
     */
    public function historyRequest()
    {
        return $this->historyRequest;
    }

    /**
     * 清空历史请求
     *
     * @return $this
     */
    public function clearHistoryRequest()
    {
        $this->historyRequest = [];
        return $this;
    }

    /**
     * 设置access token
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function setUpAccessToken()
    {
        $data = [
            'access_token' => 'access_token',
            'expires_in' => 0,
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $this->app()->access_token->getAccessToken(true);
        $this->historyRequest = [];
    }

    /**
     * 断言请求方法
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestMethod($expected, Request $request)
    {
        $this->assertEquals($expected, $request->getMethod());
    }

    /**
     * 断言请求地址
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestUri($expected, Request $request)
    {
        $this->assertTrue(strpos($request->getUri(), $expected) == 0);
    }

    /**
     * 断言请求参数
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestWithQueryParams($expected, Request $request)
    {
        $data = [];
        parse_str($request->getUri()->getQuery(),$data);

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $data[$key]);
        }
    }

    /**
     * 断言请求参数
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestWithoutQueryParams($expected, Request $request)
    {
        $data = [];
        parse_str($request->getUri()->getQuery(),$data);

        foreach ($expected as $key => $value) {
            $this->assertTrue(!isset($data[$key]));
        }
    }

    /**
     * 断言表单请求参数
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestBody($expected, Request $request)
    {
    }

    /**
     * 断言json请求参数
     *
     * @param         $expected
     * @param Request $request
     */
    public function assertRequestJsonBody($expected, Request $request)
    {
        $this->assertEquals($expected, json_decode(strval($request->getBody()), true));
    }

    /**
     * 断言请求头信息
     *
     * @param         $expectedHeaders
     * @param Request $request
     */
    public function assertRequestWithHeaders(array $expectedHeaders, Request $request)
    {
        foreach ($expectedHeaders as $header => $value) {
            $this->assertEquals($value, $request->getHeader($header));
        }
    }

    /**
     * 断言请求头信息
     *
     * @param array   $expectedHeaders
     * @param Request $request
     */
    public function assertRequestWithoutHeaders(array $expectedHeaders, Request $request)
    {
        foreach ($expectedHeaders as $header) {
            $this->assertEmpty($request->getHeader($header));
        }
    }
}

