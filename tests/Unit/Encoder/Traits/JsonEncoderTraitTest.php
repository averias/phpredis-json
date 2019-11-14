<?php
/**
 * This file is part of PhpRedisJSON library
 *
 * @project   phpredis-json
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/phpredis-json
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Averias\RedisJson\Tests\Unit\Encoder\Traits;

use Averias\RedisJson\Encoder\Traits\JsonEncoderTrait;
use Averias\RedisJson\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

class JsonEncoderTraitTest extends TestCase
{
    /**
     * @dataProvider getEncoderDataProvider
     * @param $phpData
     * @param string $jsonData
     */
    public function testEncode($phpData, string $jsonData)
    {
        $mock = $this->getMockForTrait(JsonEncoderTrait::class);
        $encoded = $mock->encode($phpData);
        $this->assertSame($jsonData, $encoded);
    }

    public function testEncodeException()
    {
        $this->expectException(ResponseException::class);
        $mock = $this->getMockForTrait(JsonEncoderTrait::class);
        $mock->encode($this->getAssociativeArrayWithDepth(513));
    }

    /**
     * @dataProvider getEncoderDataProvider
     * @param $phpData
     * @param string $jsonData
     */
    public function testDecode($phpData, string $jsonData)
    {
        $mock = $this->getMockForTrait(JsonEncoderTrait::class);
        $decoded = $mock->decode($jsonData);
        $this->assertSame($phpData, $decoded);
    }

    public function testDecodeException()
    {
        $this->expectException(ResponseException::class);
        $mock = $this->getMockForTrait(JsonEncoderTrait::class);
        $mock->decode('{"key1":"value1","key2":false,"key3:18,"key4":11.2,"key5":["array-value1","array-value2"]}');
    }

    public function getEncoderDataProvider()
    {
        return [
            [
                [
                    'key1' => 'value1',
                    'key2' => false,
                    'key3' => 18,
                    'key4' => 11.2,
                    'key5' => ['array-value1', 'array-value2']
                ],
                '{"key1":"value1","key2":false,"key3":18,"key4":11.2,"key5":["array-value1","array-value2"]}'
            ]
        ];
    }

    protected function getAssociativeArrayWithDepth(int $depth)
    {
        $leaf = ['name' => 'foo', 'age' => $depth];
        $parent = [];
        for ($i = 1; $i < $depth; $i++) {
            $parent = [
                'name' => 'foo',
                'age' => $depth - $i,
                'children' => $leaf
            ];
            $leaf =  $parent;
        }

        return $parent;
    }
}
