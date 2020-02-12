<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\ContentSecurity;

use EasyByteDance\MiniApp\ContentSecurity\ContentSecurity;
use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class ContentSecurityTest
 */
class ContentSecurityTest extends TestCase
{
    /**
     * 检查结果
     *
     * @var array
     */
    protected $checkResult = [
        'log_id' => 'log_id',
        'data' => [
            [
                'code' => 0,
                'task_id' => "task_id",
                'data_id' => "data_id",
                'cached' => false,
                'predicts' => [
                    [
                        'prob' => 1,
                        'model_name' => 'model_name',
                        'target' => 'target'
                    ]
                ],
                'msg' => 'ok',
            ]
        ],
    ];

    /**
     * 文字检查地址
     *
     * @var string
     */
    protected $checkTextEndPoint = 'https://developer.toutiao.com/api/v2/tags/text/antidirt';

    /**
     * 图片检查地址
     *
     * @var string
     */
    protected $checkImageEndPoint = 'https://developer.toutiao.com/api/v2/tags/image/';

    /**
     * Test text check
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCheckText()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($this->checkResult)
        );

        $text = 'test text';
        $this->assertEmpty($this->historyRequest());
        $this->app()->content_security->checkText($text);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];

            $this->assertRequestMethod('POST', $request);
            $this->assertRequestUri($this->checkTextEndPoint, $request);

            $params = [
                'tasks' => [
                    [
                        'content' => $text,
                    ]
                ],
            ];
            $this->assertRequestJsonBody($params, $request);

            $headers = [
                'X-Token' => [$this->app()->access_token->getAccessToken()],
                'Content-Type' => ['application/json']
            ];
            $this->assertRequestWithHeaders($headers, $request);
        }
    }

    /**
     * Test check image
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testCheckImage()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($this->checkResult)
        );

        $image = 'http://localhost/test.jpg';
        $this->assertEmpty($this->historyRequest());
        $this->app()->content_security->checkImage($image);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];

            $this->assertRequestMethod('POST', $request);
            $this->assertRequestUri($this->checkImageEndPoint, $request);

            $params = [
                'targets' => [
                    ContentSecurity::CHECK_IMAGE_TARGET_AD,
                    ContentSecurity::CHECK_IMAGE_TARGET_DISGUSTING,
                    ContentSecurity::CHECK_IMAGE_TARGET_POLITICS,
                    ContentSecurity::CHECK_IMAGE_TARGET_PORN,
                ],
                'tasks' => [
                    [
                        'image' => $image,
                    ]
                ],
            ];
            $this->assertRequestJsonBody($params, $request);

            $headers = [
                'X-Token' => [$this->app()->access_token->getAccessToken()],
                'Content-Type' => ['application/json']
            ];
            $this->assertRequestWithHeaders($headers, $request);
        }
    }
}

