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

class JsonArrayLengthCommandTest extends BaseTestIntegration
{
    public function testArrayLength()
    {
        $arrayLength = static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors');
        $this->assertEquals(2, $arrayLength);
    }

    public function testLengthOfIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.age');
    }

    public function testLengthOfFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.height');
    }

    public function testLengthOfObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.location');
    }

    public function testLengthOfBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.license');
    }

    public function testLengthOfStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.name');
    }

    public function testLengthOfEmptyArray()
    {
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors');
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors');
        $arrayLength = static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors');
        $this->assertEquals(0, $arrayLength);
    }

    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength('nonexistent', '.colors');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.nonexistent');
    }
}
