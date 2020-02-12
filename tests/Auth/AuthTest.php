<?php /** @noinspection PhpUndefinedClassInspection */


namespace EasyByteDance\MiniApp\Tests\Auth;

use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{
    /**
     * code2Session 接口地址
     *
     * @var string
     */
    protected $code2SessionEndPoint = 'https://developer.toutiao.com/api/apps/jscode2session';

    /**
     * 测试code换session
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSession()
    {
        $data = [
            'error' => 0,
            'anonymous_openid' => 'anonymous_openid',
            'openid' => 'openid',
            'session_key' => 'session_key',
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $this->assertEmpty($this->historyRequest());
        $this->app()->auth->session('code', 'anonymous_code');

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestMethod('GET', $request);
            $this->assertRequestUri($this->code2SessionEndPoint, $request);

            $params = [
                'code' => 'code',
                'anonymous_code' => 'anonymous_code',
                'appid' => $this->app()->config->get('app_id'),
                'secret' => $this->app()->config->get('app_secret'),
            ];
            $this->assertRequestWithQueryParams($params, $request);
        }
    }
}
