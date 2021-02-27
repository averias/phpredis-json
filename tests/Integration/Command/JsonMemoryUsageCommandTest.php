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

class JsonMemoryUsageCommandTest extends BaseTestIntegration
{
    public function testResponseIsAPositiveInteger()
    {
        $response = static::$reJsonClient->jsonMemoryUsage(Keys::DEFAULT_KEY);
        $this->assertIsInt($response);
        $this->assertGreaterThan(0, $response);
    }

    public function testNonExistentKeyReturnException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMemoryUsage('nonexistent');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMemoryUsage(Keys::DEFAULT_KEY, '.nonexistent');
    }
}
