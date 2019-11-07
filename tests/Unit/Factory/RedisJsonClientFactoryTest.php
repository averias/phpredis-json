<?php
/**
 * This file is part of PhpRedisJSON library
 *
 * @project   php-redis-json
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/php-redis-json
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Averias\RedisJson\Tests\Unit\Factory;

use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Exception\RedisClientException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use PHPUnit\Framework\TestCase;

class RedisJsonClientFactoryTest extends TestCase
{
    public function testCreateClientException()
    {
        $this->expectException(RedisClientException::class);
        $factory = new RedisJsonClientFactory();
        $factory->createClient([
            'database' => 62
        ]);
    }

    public function testCreateClient()
    {
        $factory = new RedisJsonClientFactory();
        $client = $factory->createClient([
            'server' => REDIS_TEST_SERVER,
            'version' => REDIS_TEST_VERSION
        ]);

        $this->assertInstanceOf(RedisJsonClientInterface::class, $client);
    }
}
