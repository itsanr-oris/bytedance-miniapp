<?php /** @noinspection PhpUndefinedClassInspection */


namespace EasyByteDance\MiniApp\TemplateMessage;

use EasyByteDance\MiniApp\Component;
use EasyByteDance\MiniApp\Exceptions\InvalidArgumentException;

/**
 * Class TemplateMessage
 */
class TemplateMessage extends Component
{
    /**
     * 模板消息发送接口地址
     *
     * @var string
     */
    protected $sendMessageEndPoint = 'game/template/send';

    /**
     * 模板消息数据格式
     *
     * @var array
     */
    protected $message = [
        'access_token' =>'',
        'touser' => '',
        'template_id' => '',
        'page' => '',
        'form_id' => '',
        'data' => [],
    ];

    /**
     * 模板消息发送必须提供的数据字段
     *
     * @var array
     */
    protected $required = [
        'access_token',
        'touser',
        'template_id',
        'form_id',
        'data'
    ];

    /**
     * Send template message
     *
     * @param array $data
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function send(array $data = [])
    {
        return $this->http()->postJson($this->sendMessageEndPoint, $this->format($data));
    }

    /**
     * 格式化数据信息
     *
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     * @throws \EasyByteDance\MiniApp\Exceptions\ResponseException
     * @throws \Foris\Easy\Cache\InvalidConfigException
     * @throws \Foris\Easy\Cache\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function format(array $data = [])
    {
        $message = array_merge($this->message, $data);
        $message['app_id'] = $this->app()->config->get('app_id');
        $message['access_token'] = $this->app()->access_token->getAccessToken();

        foreach ($this->required as $filed) {
            if (empty($message[$filed])) {
                throw new InvalidArgumentException(sprintf('Param "%s" can not be empty!', $filed));
            }
        }

        foreach ($message['data'] as $keyword => $value) {
            if (strpos($keyword, 'keyword') !== 0) {
                throw new InvalidArgumentException('Message data key must be prefixed with keyword, eg: keyword1!');
            }

            if (!isset($value['value'])) {
                throw new InvalidArgumentException(sprintf('Message data key "%s" can not be empty!', $keyword));
            }
        }

        return $message;
    }
}
