<?php /** @noinspection PhpUndefinedClassInspection */

namespace EasyByteDance\MiniApp\ContentSecurity;

use EasyByteDance\MiniApp\Component;

/**
 * Class ContentSecurity
 */
class ContentSecurity extends Component
{
    /**
     * 图片检查target定义
     */
    const CHECK_IMAGE_TARGET_PORN ='porn';
    const CHECK_IMAGE_TARGET_POLITICS = 'politics';
    const CHECK_IMAGE_TARGET_AD = 'ad';
    const CHECK_IMAGE_TARGET_DISGUSTING = 'disgusting';

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
     * Text content security check.
     *
     * @param string $text
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkText($text)
    {
        $tasks = [];
        $text = is_array($text) ? $text : (array) $text;

        foreach ($text as $content) {
            $tasks[] = ['content' => $content];
        }

        return $this->http()->postJson($this->checkTextEndPoint, ['tasks' => $tasks], [], ['base_uri' => '']);
    }

    /**
     * Image security check.
     *
     * @param string $images
     * @param array  $targets
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkImage($images, $targets = [])
    {
        $tasks = [];
        $images = is_array($images) ? $images : (array) $images;

        foreach ($images as $image) {
            $tasks[] = ['image' => $image];
        }

        if (empty($targets)) {
            $targets = [
                self::CHECK_IMAGE_TARGET_AD,
                self::CHECK_IMAGE_TARGET_DISGUSTING,
                self::CHECK_IMAGE_TARGET_POLITICS,
                self::CHECK_IMAGE_TARGET_PORN,
            ];
        }

        $params = [
            'targets' => $targets, 'tasks' => $tasks
        ];
        return $this->http()->postJson($this->checkImageEndPoint, $params, [], ['base_uri' => '']);
    }
}

