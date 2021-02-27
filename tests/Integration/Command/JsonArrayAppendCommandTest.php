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

namespace Averias\RedisJson\Tests\Integration\Command;

use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Enum\Keys;
use Averias\RedisJson\Tests\Integration\BaseTestIntegration;

class JsonArrayAppendCommandTest extends BaseTestIntegration
{
    /**
     * @dataProvider getValuesForAdding
     * @param $value
     */
    public function testAppendValuesToArray($value)
    {
        $previousValues = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');
        $arraySize = static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.colors', $value);

        $previousValues[] = $value;

        $this->assertEquals(count($previousValues), $arraySize);

        $storedArray = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');

        $this->assertSame($previousValues, $storedArray);
    }

    public function testAppendMultipleValuesToArray()
    {
        $previousValues = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');

        $multipleValues = ['multiple1', 'multiple2', 'multiple3'];
        $arraySize = static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.colors', ...$multipleValues);

        $previousValues = array_merge($previousValues, $multipleValues);

        $this->assertEquals(count($previousValues), $arraySize);

        $storedArray = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');

        $this->assertSame($previousValues, $storedArray);
    }

    public function testAppendToIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.age', 'orange');
    }

    public function testAppendToFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.height', 'orange');
    }

    public function testAppendToObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.location', 'orange');
    }

    public function testAppendToBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.license', 'orange');
    }

    public function testAppendToStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.name', 'orange');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend('nonexistent', '.colors', 'orange');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.nonexistent', 'orange');
    }

    public function getValuesForAdding()
    {
        return [
            ['green'],
            [23],
            [17.5],
            ['48'],
            ['18.5'],
            [['pink', 'grey']],
            [true],
            ["false"],
            [
                [
                    'key1' => 'value1',
                    'key2' => false,
                    'key3' => 18,
                    'key4' => 11.2,
                    'key5' => ['array-value1', 'array-value2']
                ]
            ]
        ];
    }
}
