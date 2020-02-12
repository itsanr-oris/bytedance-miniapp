<?php

namespace EasyByteDance\MiniApp\Tests\Encryptor;

use EasyByteDance\MiniApp\Encryptor\Encryptor;
use EasyByteDance\MiniApp\Exceptions\DecryptException;
use EasyByteDance\MiniApp\Exceptions\RunTimeException;
use EasyByteDance\MiniApp\Tests\TestCase;

/**
 * Class EncryptorTest
 */
class EncryptorTest extends TestCase
{
    /**
     * Test data signature
     */
    public function testSignature()
    {
        $data = 'test_data';
        $sessionKey = 'session_key';

        $sha1Signature = sha1($data . $sessionKey);
        $this->assertEquals($sha1Signature, $this->app()->encryptor->signature($data, $sessionKey));

        // official example
        $data = '{"foo":"bar"}';
        $sessionKey = '724edcafc423d167724edcbe';
        $expectSignature = '44b5092fa1c9adba03803239934d4958b8a1840adf0cee8d5e95c1cf5d495e0e';
        $method = Encryptor::SIGN_METHOD_HMAC_SHA256;
        $this->assertEquals($expectSignature, $this->app()->encryptor->signature($data, $sessionKey, $method));

        $this->assertFalse($this->app()->encryptor->signature($data, $sessionKey, 'not exist method'));
    }

    /**
     * Test encrypt data
     *
     * @throws \Exception
     */
    public function testEncryptData()
    {
        $data = [
            'openId' => 'openid',
            'nickName' => 'nickname',
            'avatarUrl' => 'avatar url',
            'gender' => 1,
            'country' => '中国',
            'province' => '广东',
            'city' => '广州',
            'language' => 'zh-CN',
            'watermark' => [
                'appid' => 'appid',
                'timestamp' => 1573737950
            ]
        ];

        $sessionKey = 'c40ES1C425FhsxXjT0iWPA==';
        $iv = 'OoA5fvKEFlWf+rk7cKsnXA==';
        $encryptedData = 'uTBJKRJ6AGKdstg4xNb/ktZbAB5caUU7N1tObv8/TxEYIdAQd9ViAwd5feULWyCC1roREnkAmn7o5wuI5Rr91Tirn6QwXjcs99KjeOmIg9V+38T99eFmPuFRVH2UMozLkWzRNnEu+3OZSn4VRVVG97Jo64d976Jknw/bMBOWmq+2DsYfnRXGTKFrksLP+pS+E1POdChTJVzg7feSIOxQjFiXW2/0GpFOpRBSgzN07ljHb3cHwRQN6rBwYAW0R75Va/0nPKHc14rJLVUyUHH5yQ==';

        $this->assertEquals($encryptedData, $this->app()->encryptor->encryptData($sessionKey, $iv, $data));
    }

    /**
     * Test decrypt data
     *
     * @throws \EasyByteDance\MiniApp\Exceptions\DecryptException
     */
    public function testDecryptData()
    {
        $sessionKey = 'c40ES1C425FhsxXjT0iWPA==';
        $iv = 'OoA5fvKEFlWf+rk7cKsnXA==';
        $encryptedData = 'uTBJKRJ6AGKdstg4xNb/ktZbAB5caUU7N1tObv8/TxEYIdAQd9ViAwd5feULWyCC1roREnkAmn7o5wuI5Rr91Tirn6QwXjcs99KjeOmIg9V+38T99eFmPuFRVH2UMozLkWzRNnEu+3OZSn4VRVVG97Jo64d976Jknw/bMBOWmq+2DsYfnRXGTKFrksLP+pS+E1POdChTJVzg7feSIOxQjFiXW2/0GpFOpRBSgzN07ljHb3cHwRQN6rBwYAW0R75Va/0nPKHc14rJLVUyUHH5yQ==';
        $decryptData = $this->app()->encryptor->decryptData($sessionKey, $iv, $encryptedData);

        $data = [
            'openId' => 'openid',
            'nickName' => 'nickname',
            'avatarUrl' => 'avatar url',
            'gender' => 1,
            'country' => '中国',
            'province' => '广东',
            'city' => '广州',
            'language' => 'zh-CN',
            'watermark' => [
                'appid' => 'appid',
                'timestamp' => 1573737950
            ]
        ];

        $this->assertEquals($data, $decryptData);
    }

    /**
     * Test decrypt data failure
     *
     * @throws DecryptException
     */
    public function testDecryptDataFailure()
    {
        $sessionKey = 'session_key';
        $iv = 'iviviviviviviviviviviv==';
        $encryptedData = 'encrypted_data';

        $this->expectException(DecryptException::class);
        $this->expectExceptionMessage('The given payload is invalid.');
        $this->app()->encryptor->decryptData($sessionKey, $iv, $encryptedData);
    }

    /**
     * Test pkcs 7 padding block size great than 256 exception
     *
     * @throws RunTimeException
     */
    public function testPkcs7PaddingBlockSizeGreatThan256Exception()
    {
        $this->expectException(RunTimeException::class);
        $this->expectExceptionMessage('$blockSize may not be more than 256');
        $this->app()->encryptor->pkcs7Pad('test', 257);
    }
}

