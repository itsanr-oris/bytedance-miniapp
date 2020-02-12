<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Auth;

use EasyByteDance\MiniApp\Component;
use EasyByteDance\MiniApp\Exceptions\ResponseException;
use Foris\Easy\HttpClient\ResponseHandler;

/**
 * Class AccessToken
 */
class AccessToken extends Component
{
    /**
     * access token请求地址
     *
     * @var string
     */
    protected $endpointToGetToken = 'token';

    /**
     * access token缓存前缀
     *
     * @var string
     */
    protected $cachePrefix = 'easy-toutiao.mini-program.access_token.';

    /**
     * 获取请求凭证信息
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'appid' => $this->app['config']['app_id'],
            'secret' => $this->app['config']['app_secret'],
            'grant_type' => 'client_credential',
        ];
    }

    /**
     * 获取access token缓存键值
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix . md5(json_encode($this->getCredentials()));
    }

    /**
     * 请求接口获取access token信息
     *
     * @param bool $refresh
     * @return array|null
     * @throws ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function accessTokenRequest($refresh = false)
    {
        $key = $this->getCacheKey();
        if (!$refresh && $this->cache()->has($key)) {
            return $this->cache()->get($key);
        }

        $client = $this->http();
        $client->withAccessToken(false);
        $client->setResponseType(ResponseHandler::TYPE_ARRAY);
        $response = $client->get($this->endpointToGetToken, $this->getCredentials());

        if (!empty($response['errcode'])) {
            throw new ResponseException(sprintf('Access token response error[%s]', $response['errcode']));
        }

        $this->cache()->set($key, $response);
        return $response;
    }

    /**
     * 获取access token信息
     *
     * @param bool $refresh
     * @return mixed|null
     * @throws ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAccessToken($refresh = false)
    {
        $response = $this->accessTokenRequest($refresh);
        return $response['access_token'] ?? null;
    }

    /**
     * 获取请求携带参数
     *
     * @return array
     * @throws ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getQuery()
    {
        return ['access_token' => $this->getAccessToken()];
    }
}
