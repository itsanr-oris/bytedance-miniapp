<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\AppCode;

use EasyByteDance\MiniApp\Component;

/**
 * Class QRCode
 */
class AppCode extends Component
{
    /**
     * 是打开二维码的字节系 app 名称
     */
    const APP_NAME_TOUTIAO = 'toutiao';
    const APP_NAME_DOUYIN = 'douyin';
    const APP_NAME_PIPIXIA = 'pipixia';
    const APP_NAME_HUOSHAN = 'huoshan';

    /**
     * 创建小程序二维码接口地址
     *
     * @var string
     */
    protected $createEndpoint = 'qrcode';

    /**
     * Get app code
     *
     * @param string $path
     * @param array  $optional
     * @return string
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $path = '', array $optional = [])
    {
        $optional = array_merge($optional, ['path' => $path]);
        $optional['path'] = urlencode($optional['path']);
        $optional['access_token'] = $this->app()->access_token->getAccessToken();

        return strval($this->http()->postJson($this->createEndpoint, $optional));
    }
}
