<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\AppCode;

use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class AppCodeTest
 */
class AppCodeTest extends TestCase
{
    /**
     * 接口地址
     *
     * @var string
     */
    protected $createQrCodeEndPoint = 'https://developer.toutiao.com/api/apps/qrcode';

    /**
     * 测试获取二维码
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetAppCode()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'image/png'],
            "qr code image string buffer"
        );

        $path = 'test/path';
        $options = [
            'appname' => 'douyin',
            'path' => '',
            'width' => 400,
            'line_color' => ['r' => 255, 'g' => 255, 'b' => 255],
            'background' => ['r' => 255, 'g' => 255, 'b' => 255],
            'set_icon' => false,
        ];

        // 断言未执行接口前没有任何请求
        $this->assertEmpty($this->historyRequest());
        $this->app()->app_code->get($path, $options);

        // 断言请求后有一个请求记录
        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestMethod('POST', $request);
            $this->assertRequestUri($this->createQrCodeEndPoint, $request);

            // 断言请求参数
            $params = $options;
            $params['path'] = urlencode($path);
            $params['access_token'] = $this->app()->access_token->getAccessToken();
            $this->assertRequestJsonBody($params, $request);

            // 因为是以json格式提交数据，所以额外断言请求头 Content-Type: application/json
            $headers = [
                'Content-Type' => ['application/json']
            ];
            $this->assertRequestWithHeaders($headers, $request);
        }

    }
}
