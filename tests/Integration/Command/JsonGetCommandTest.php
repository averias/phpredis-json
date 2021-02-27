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

class JsonGetCommandTest extends BaseTestIntegration
{
    public function testGetFullData()
    {
        $this->assertSame(static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY), static::$defaultData);
    }

    public function testGetOnePathNoRoot()
    {
        $this->assertSame(static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'), static::$defaultData['colors']);
    }

    public function testMultiplePaths()
    {
        $multipleStoredPath = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.name', '.location');

        $this->assertSame($multipleStoredPath['.name'], static::$defaultData['name']);
        $this->assertSame($multipleStoredPath['.location'], static::$defaultData['location']);
    }

    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonGet('nonexistent');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.nonexistent');
    }
}
