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

class JsonObjectLengthCommandTest extends BaseTestIntegration
{
    public function testKeysLengthInDefaultDataObject()
    {
        $expectedKeysLength = count(array_keys(static::$defaultData));
        $storedKeysLength = static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY);
        $this->assertSame($expectedKeysLength, $storedKeysLength);

        $expectedKeysLength = count(array_keys(static::$defaultData['location']));
        $storedKeysLength = static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.location');
        $this->assertSame($expectedKeysLength, $storedKeysLength);
    }

    public function testKeysLengthInArrayPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.colors');
    }

    public function testKeysLengthInStringPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.name');
    }

    public function testKeysLengthInIntegerPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.age');
    }

    public function testKeysLengthInFloatPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.height');
    }

    public function testKeysLengthInBooleanPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.license');
    }

    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength('nonexistent');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength(Keys::DEFAULT_KEY, '.nonexistent');
    }

    public function testEmptyObjectException()
    {
        static::$reJsonClient->jsonSet(Keys::KEY_TO_EMPTY, ['foo' => 'bar']);
        static::$reJsonClient->jsonDelete(Keys::KEY_TO_EMPTY, 'foo');
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectLength('empty-key');
    }
}
