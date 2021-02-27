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

class JsonStringAppendCommandTest extends BaseTestIntegration
{
    public function testAppendString()
    {
        $length = static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, ' Newman', '.name');
        $this->assertEquals(strlen('Peter Newman'), $length);

        $result = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.name');
        $this->assertEquals('Peter Newman', $result, true);
    }

    public function testAppendStringOnNumberPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, 'years', '.age');
    }

    public function testAppendStringOnObjectPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, 'years', '.location');
    }

    public function testAppendStringOnArrayPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, 'years', '.colors');
    }

    public function testAppendStringOnBooleanPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, 'years', '.license');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend('nonexistent', 'abc');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringAppend(Keys::DEFAULT_KEY, 'abc', '.nonexistent');
    }
}
