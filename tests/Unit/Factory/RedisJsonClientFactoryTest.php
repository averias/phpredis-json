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

namespace Averias\RedisJson\Tests\Unit\Factory;

use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Exception\RedisClientException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use Averias\RedisJson\Enum\Connection;
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
            Connection::HOST => REDIS_TEST_SERVER,
            Connection::PORT => (int) REDIS_TEST_PORT,
            Connection::TIMEOUT => 2
        ]);

        $this->assertInstanceOf(RedisJsonClientInterface::class, $client);
    }
}
