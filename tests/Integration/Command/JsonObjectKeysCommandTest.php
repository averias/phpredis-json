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

class JsonObjectKeysCommandTest extends BaseTestIntegration
{
    public function testKeysInDefaultDataObject()
    {
        $expectedKeys = array_keys(static::$defaultData);
        $storedKeys = static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY);
        $this->assertSame($expectedKeys, $storedKeys);

        $expectedKeys = array_keys(static::$defaultData['location']);
        $storedKeys = static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.location');
        $this->assertSame($expectedKeys, $storedKeys);
    }

    public function testKeysInArrayPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.colors');
    }

    public function testKeysInStringPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.name');
    }

    public function testKeysInIntegerPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.age');
    }

    public function testKeysInFloatPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.height');
    }

    public function testKeysInBooleanPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.license');
    }

    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys('nonexistent');
    }

    public function testNonExistentPathReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonObjectKeys(Keys::DEFAULT_KEY, '.nonexistent');
    }

    public function testEmptyObjectReturnsEmptyArrayKeys()
    {
        static::$reJsonClient->jsonSet(Keys::KEY_TO_EMPTY, ['foo' => 'bar']);
        static::$reJsonClient->jsonDelete(Keys::KEY_TO_EMPTY, 'foo');
        $this->assertEmpty(static::$reJsonClient->jsonObjectKeys(Keys::KEY_TO_EMPTY));
    }
}
