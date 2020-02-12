<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\Tests\TemplateMessage;

use EasyByteDance\MiniApp\Exceptions\InvalidArgumentException;
use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class TemplateMessageTest
 */
class TemplateMessageTest extends TestCase
{
    /**
     * 模板消息发送地址
     *
     * @var string
     */
    protected $sendTemplateEndpoint = 'https://developer.toutiao.com/api/apps/game/template/send';

    /**
     * Test send template message
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testSendTemplateMessage()
    {
        $message  = [
            'touser' => 'openid',
            'template_id' => 'template_id',
            'page' => '',
            'form_id' => 'form_id',
            'data' => [
                "keyword1" => [
                    "value" => "F-oris"
                ],
                "keyword2" => [
                    "value" => "广东广州"
                ],
                "keyword3" => [
                    "value" => "2019-11-11 12:00:00",
                ]
            ],
        ];

        $data = [
            'errcode' => 0,
            'errmsg' => 'success',
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $this->assertEmpty($this->historyRequest());
        $this->app()->template_message->send($message);

        $historyRequest = $this->historyRequest();
        $this->assertCount(1, $historyRequest);

        if (count($historyRequest) == 1) {
            $request = $historyRequest[0]['request'];
            $this->assertRequestUri($this->sendTemplateEndpoint, $request);
            $this->assertRequestMethod('POST', $request);
        }
    }

    /**
     * 测试非法参数
     *
     * @throws InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidParamException()
    {
        $message  = [
            'touser' => '',
            'template_id' => 'template_id',
            'page' => '',
            'form_id' => 'form_id',
            'data' => [
                "keyword1" => [
                    "value" => "F-oris"
                ],
                "keyword2" => [
                    "value" => "广东广州"
                ],
                "keyword3" => [
                    "value" => "2019-11-11 12:00:00",
                ]
            ],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param "touser" can not be empty!');
        $this->app()->template_message->send($message);
    }

    /**
     * 测试非法data key
     *
     * @throws InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidDataKeyException()
    {
        $message  = [
            'touser' => 'openid',
            'template_id' => 'template_id',
            'page' => '',
            'form_id' => 'form_id',
            'data' => [
                "invalid-key" => [
                    "value" => "F-oris"
                ],
            ],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Message data key must be prefixed with keyword, eg: keyword1!');
        $this->app()->template_message->send($message);
    }

    /**
     * 测试非法data value
     *
     * @throws InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidDataValueException()
    {
        $message  = [
            'touser' => 'openid',
            'template_id' => 'template_id',
            'page' => '',
            'form_id' => 'form_id',
            'data' => [
                "keyword1" => [
                    "invalid-value" => "F-oris"
                ],
            ],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Message data key "keyword1" can not be empty!');
        $this->app()->template_message->send($message);
    }
}
