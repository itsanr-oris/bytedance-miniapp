<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\UserStorage;

use EasyByteDance\MiniApp\Encryptor\Encryptor;
use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class UserStorageTest
 */
class UserStorageTest extends TestCase
{
    /**
     * 设置数据缓存api
     *
     * @var string
     */
    protected $setUserStorageEndpoint = 'https://developer.toutiao.com/api/apps/set_user_storage';

    /**
     * 删除数据缓存api
     *
     * @var string
     */
    protected $removeUserStorageEndpoint = 'https://developer.toutiao.com/api/apps/remove_user_storage';

    /**
     * 测试设置数据缓存
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSetData()
    {
        $openid = 'openid';
        $sessionKey = 'session_key';
        $kvList = [
            [
                'key' => 'test',
                'value' => json_encode(['tt_game' => ['score' => 1]])
            ]
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 0])
        );

        $this->assertEmpty($this->historyRequest());
        $this->app()->user_storage->set($openid, $sessionKey, $kvList);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestUri($this->setUserStorageEndpoint, $request);
            $this->assertRequestMethod('POST', $request);

            $signatureMethod = Encryptor::SIGN_METHOD_HMAC_SHA256;
            $signature = $this->app()->encryptor->signature(json_encode(['kv_list' => $kvList]), $sessionKey, $signatureMethod);

            $queryParams = [
                'openid' => $openid,
                'sig_method' => $signatureMethod,
                'signature' => $signature
            ];

            $this->assertRequestWithQueryParams($queryParams, $request);
            $this->assertRequestJsonBody(['kv_list' => $kvList], $request);
        }
    }

    /**
     * 测试删除数据缓存
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRemoveData()
    {
        $openid = 'openid';
        $sessionKey = 'session_key';
        $keys = ['test'];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 0])
        );

        $this->assertEmpty($this->historyRequest());
        $this->app()->user_storage->remove($openid, $sessionKey, $keys);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestUri($this->setUserStorageEndpoint, $request);
            $this->assertRequestMethod('POST', $request);

            $signatureMethod = Encryptor::SIGN_METHOD_HMAC_SHA256;
            $signature = $this->app()->encryptor->signature(json_encode(['key' => $keys]), $sessionKey, $signatureMethod);

            $queryParams = [
                'openid' => $openid,
                'sig_method' => $signatureMethod,
                'signature' => $signature
            ];

            $this->assertRequestWithQueryParams($queryParams, $request);
            $this->assertRequestJsonBody(['key' => $keys], $request);
        }
    }
}
