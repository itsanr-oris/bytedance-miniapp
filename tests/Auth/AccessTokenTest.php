<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\Auth;

use EasyByteDance\MiniApp\Exceptions\ResponseException;
use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class AccessTokenTest
 */
class AccessTokenTest extends TestCase
{
    /**
     * 获取access token地址
     *
     * @var string
     */
    protected $getAccessTokenEndPoint = 'https://developer.toutiao.com/api/apps/token';

    /**
     * Test get access token
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetAccessToken()
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

        // 断言发起请求前没有历史请求记录，发起请求后有请求记录
        $this->assertEmpty($this->historyRequest());
        $this->app()->access_token->getAccessToken(true);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestMethod('GET', $request);
            $this->assertRequestUri($this->getAccessTokenEndPoint, $request);

            $params = [
                'appid' => $this->app()->config->get('app_id'),
                'secret' => $this->app()->config->get('app_secret'),
                'grant_type' => 'client_credential',
            ];
            $this->assertRequestWithQueryParams($params, $request);

            // 断言上一次获取的access token已经被缓存
            $this->clearHistoryRequest();
            $this->assertEmpty($this->historyRequest());
            $this->assertSame('access_token', $this->app()->access_token->getAccessToken());
            $this->assertEmpty($this->historyRequest());
        }
    }

    /**
     * Test get access token with error response
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetAccessTokenWithErrorResponse()
    {
        $data = [
            'errcode' => 40001,
            'errmsg' => 'test get access token error',
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage('Access token response error[40001]');
        $this->app()->access_token->getAccessToken(true);
    }

    /**
     * Test get access token query params
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetAccessTokenQueryParams()
    {
        $params = $this->app()->access_token->getQuery();
        $this->assertTrue(isset($params['access_token']));
    }
}

