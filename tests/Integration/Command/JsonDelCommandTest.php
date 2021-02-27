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

class JsonDelCommandTest extends BaseTestIntegration
{
    public function testPartialDeletion()
    {
        $this->assertTrue(static::$reJsonClient->jsonDelete(Keys::DEFAULT_KEY, '.location'));

        $storedData = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY);
        $this->assertFalse(isset($storedData['location']));
    }

    public function testFullDeletion()
    {
        $this->assertTrue(static::$reJsonClient->jsonDelete(Keys::DEFAULT_KEY));

        // after deleting the complete object, key doesn't exist in Redis any more and a exception is thrown
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY);
    }

    public function testNonExistentKeyReturnsFalse()
    {
        $this->assertFalse(static::$reJsonClient->jsonDelete('nonexistent'));
    }

    public function testNonExistentPathReturnsFalse()
    {
        $this->assertFalse(static::$reJsonClient->jsonDelete(Keys::DEFAULT_KEY, '.nonexistent'));
    }
}
