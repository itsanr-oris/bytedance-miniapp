<?php

namespace EasyByteDance\MiniApp\Tests\UserStorage;

use EasyByteDance\MiniApp\Exceptions\InvalidArgumentException;
use EasyByteDance\MiniApp\Tests\TestCase;
use EasyByteDance\MiniApp\UserStorage\KeyValueCollection;

/**
 * Class KeyValueCollectionTest
 */
class KeyValueCollectionTest extends TestCase
{
    /**
     * 测试添加单个key-value信息
     *
     * @throws InvalidArgumentException
     */
    public function testAddItem()
    {
        $collection = new KeyValueCollection();
        $collection->addItem('test', ['ttgame' => ['score' => 100]]);

        $expected = [
            [
                'key' => 'test',
                'value' => json_encode(['ttgame' => ['score' => 100]]),
            ]
        ];
        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * 测试添加多个key-value信息
     *
     * @throws InvalidArgumentException
     */
    public function testAddItems()
    {
        $collection = new KeyValueCollection();

        $collection->addItems([
            [
                'key' => 'test-1',
                'value' => 'value',
            ],
            [
                'key' => 'test-2',
                'value' => ['ttgame' => ['score' => 100]]
            ]
        ]);

        $expected = [
            [
                'key' => 'test-1',
                'value' => 'value',
            ],
            [
                'key' => 'test-2',
                'value' => json_encode(['ttgame' => ['score' => 100]]),
            ]
        ];

        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * 测试添加不合法的key-value信息
     *
     * @throws InvalidArgumentException
     */
    public function testAddInvalidItemsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key-value list item must contain "key" and "value"!');
        (new KeyValueCollection())->addItems([['invalid key' => 'invalid key', 'invalid value' => 'invalid value']]);
    }
}
