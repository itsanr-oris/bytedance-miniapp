<?php

namespace EasyByteDance\MiniApp\Encryptor;

use EasyByteDance\MiniApp\Component;
use EasyByteDance\MiniApp\Exceptions\DecryptException;
use EasyByteDance\MiniApp\Exceptions\RunTimeException;

/**
 * Class Encryptor
 */
class Encryptor extends Component
{
    /**
     * 签名方法
     */
    const SIGN_METHOD_SHA1 = 'sha1';
    const SIGN_METHOD_HMAC_SHA256 = 'hmac_sha256';

    /**
     * block size
     *
     * @var int
     */
    protected $blockSize = 16;

    /**
     * Encrypt method
     *
     * @var string
     */
    protected $method = 'AES-128-CBC';

    /**
     * Encrypt options
     *
     * @var int
     */
    protected $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;

    /**
     * PKCS#7 pad.
     *
     * @param string $text
     * @param int    $blockSize
     *
     * @return string
     * @throws RunTimeException
     */
    public function pkcs7Pad(string $text, int $blockSize): string
    {
        if ($blockSize > 256) {
            throw new RunTimeException('$blockSize may not be more than 256');
        }
        $padding = $blockSize - (strlen($text) % $blockSize);
        $pattern = chr($padding);

        return $text.str_repeat($pattern, $padding);
    }

    /**
     * PKCS#7 unpad.
     *
     * @param string $text
     *
     * @return string
     */
    public function pkcs7Unpad(string $text): string
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * 计算数据签名
     *
     * @param string $rawData
     * @param string $sessionKey
     * @param string $method
     * @return string
     */
    public function signature(
        string $rawData,
        string $sessionKey,
        $method = self::SIGN_METHOD_SHA1
    ){
        if ($method == self::SIGN_METHOD_SHA1) {
            return sha1($rawData . $sessionKey);
        }

        if ($method == self::SIGN_METHOD_HMAC_SHA256) {
            return hash_hmac('sha256', $rawData, $sessionKey);
        }

        return false;
    }

    /**
     * Decrypt data.
     *
     * @param string $sessionKey
     * @param string $iv
     * @param string $encrypted
     *
     * @return array
     * @throws DecryptException
     */
    public function decryptData(string $sessionKey, string $iv, string $encrypted): array
    {
        $plainText = openssl_decrypt(
            base64_decode($encrypted), $this->method, base64_decode($sessionKey), $this->options, base64_decode($iv)
        );

        $decryptData = json_decode($this->pkcs7Unpad($plainText), true);

        if ($decryptData == false) {
            throw new DecryptException('The given payload is invalid.');
        }

        return $decryptData;
    }

    /**
     * Encrypt data.
     *
     * @param string $sessionKey
     * @param string $iv
     * @param array  $data
     * @return string
     * @throws \Exception
     */
    public function encryptData(string $sessionKey, string $iv, $data = []) : string
    {
        // 反加密字节跳动小程序获取到的授权信息，发现php json_encode出来的json字符串与解密得到的不一致，要特殊处理
        $str = str_replace('\\', '', json_encode($data, JSON_UNESCAPED_UNICODE));

        $plainText = $this->pkcs7Pad($str, $this->blockSize);

        $encryptText = openssl_encrypt(
            $plainText, $this->method, base64_decode($sessionKey), $this->options, base64_decode($iv)
        );
        return base64_encode($encryptText);
    }
}

