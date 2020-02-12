<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\Http;

use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class HttpClientTest
 */
class HttpClientTest extends TestCase
{
    /**
     * Test request without access token
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestWithOutAccessToken()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => 'test request'])
        );

        $uri = 'htto://localhost/test';
        $this->assertEmpty($this->historyRequest());
        $this->http()->withAccessToken(false)->get($uri, [], ['base_uri' => '']);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestUri($uri, $request);
            $this->assertRequestMethod('GET', $request);

            $this->assertRequestWithQueryParams([], $request);
            $this->assertRequestWithoutHeaders(['X-Token'], $request);
        }
    }

    /**
     * Test request with access token
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @depends testRequestWithOutAccessToken
     */
    public function testRequestWithAccessToken()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => 'test request'])
        );

        $uri = 'htto://localhost/test';
        $this->assertEmpty($this->historyRequest());
        $this->http()->get($uri, [], ['base_uri' => '']);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestUri($uri, $request);
            $this->assertRequestMethod('GET', $request);

            $token = $this->app()->access_token->getAccessToken();
            $this->assertRequestWithQueryParams(['access_token' => $token], $request);
            $this->assertRequestWithHeaders(['X-Token' => [$token]], $request);
        }
    }
}
