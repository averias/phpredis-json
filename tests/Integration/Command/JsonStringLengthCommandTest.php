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

class JsonStringLengthCommandTest extends BaseTestIntegration
{
    /**
     * @dataProvider getStringPathsProvider
     * @param string $path
     * @param int $length
     */
    public function testStringPathLength(string $path, int $length)
    {
        $result = static::$reJsonClient->jsonStringLength(Keys::DEFAULT_KEY, $path);
        $this->assertEquals($length, $result);
    }

    /**
     * @dataProvider getNonStringPathsProvider
     * @param string $path
     */
    public function testPathTypeIsNotStringException(string $path)
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringLength(Keys::DEFAULT_KEY, $path);
    }

    /**
     * a nonexistent key return NULL
     */
    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringLength('nonexistent');
    }

    /**
     * a nonexistent path throws an exception
     */
    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonStringLength(Keys::DEFAULT_KEY, '.nonexistent');
    }

    public function getStringPathsProvider()
    {
        return [
            ['.name', 5],
            ['.location.address', 14],
            ['.location.city', 8],
            ['.location.country', 7]
        ];
    }

    public function getNonStringPathsProvider()
    {
        return [
            ['.age'],
            ['.height'],
            ['.colors'],
            ['.license'],
            ['.']
        ];
    }
}
