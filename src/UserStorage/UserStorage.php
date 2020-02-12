<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\UserStorage;

use EasyByteDance\MiniApp\Component;
use EasyByteDance\MiniApp\Encryptor\Encryptor;
use EasyByteDance\MiniApp\Exceptions\InvalidArgumentException;

/**
 * Class UserStorage
 */
class UserStorage extends Component
{
    /**
     * 设置数据缓存地址
     *
     * @var string
     */
    protected $setStorageEndpoint = 'set_user_storage';

    /**
     * 清除数据缓存地址
     *
     * @var string
     */
    protected $removeStorageEndpoint = 'remove_user_storage';

    /**
     * 设置用户缓存数据
     *
     * @param string $openid
     * @param string $sessionKey
     * @param array  $kvList
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function set(string $openid, string $sessionKey, $kvList = [])
    {
        if (!$kvList instanceof KeyValueCollection) {
            $kvList = new KeyValueCollection($kvList);
        }

        $signatureMethod = Encryptor::SIGN_METHOD_HMAC_SHA256;
        $signature = $this->app()->encryptor->signature(json_encode(['kv_list' => $kvList]), $sessionKey, $signatureMethod);

        $query = [
            'openid' => $openid,
            'sig_method' => $signatureMethod,
            'signature' => $signature,
        ];

        return $this->http()->postJson($this->setStorageEndpoint, ['kv_list' => $kvList], $query);
    }

    /**
     * 删除用户数据缓存
     *
     * @param string $openid
     * @param string $sessionKey
     * @param array  $keys
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function remove(string $openid, string $sessionKey, array $keys = [])
    {
        $signatureMethod = Encryptor::SIGN_METHOD_HMAC_SHA256;
        $signature = $this->app()->encryptor->signature(json_encode(['key' => $keys]), $sessionKey, $signatureMethod);

        $query = [
            'openid' => $openid,
            'sig_method' => $signatureMethod,
            'signature' => $signature,
        ];

        return $this->http()->postJson($this->removeStorageEndpoint, ['key' => $keys], $query);
    }
}

