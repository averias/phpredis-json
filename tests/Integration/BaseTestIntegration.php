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

namespace Averias\RedisJson\Tests\Integration;

use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Exception\RedisClientException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use Averias\RedisJson\Enum\Connection;
use Averias\RedisJson\Enum\Keys;
use PHPUnit\Framework\TestCase;

class BaseTestIntegration extends TestCase
{
    public static $defaultData = [
        'name' => 'Peter',
        'age' => 38,
        'height' => 1.79,
        'location' => [
            'address' => 'Pub Street, 39',
            'city' => 'Limerick',
            'country' => 'Ireland'
        ],
        'colors' => ['white', 'black'],
        'license' => true
    ];

    /** @var RedisJsonClientInterface */
    protected static $reJsonClient;

    /**
     * @throws RedisClientException
     */
    public static function setUpBeforeClass(): void
    {
        static::$reJsonClient  = self::getReJsonClient();
        static::storeData(Keys::DEFAULT_KEY, static::$defaultData);
    }

    public static function tearDownAfterClass(): void
    {
        if (static::$reJsonClient) {
            static::$reJsonClient->select(REDIS_TEST_DATABASE);
            static::$reJsonClient->flushDb();
        }
    }

    protected static function getReJsonClientConfig(): array
    {
        return [
            Connection::HOST => REDIS_TEST_SERVER,
            Connection::PORT => (int) REDIS_TEST_PORT,
            Connection::TIMEOUT => 2,
            Connection::DATABASE => (int) REDIS_TEST_DATABASE
        ];
    }

    /**
     * @return RedisJsonClientInterface
     * @throws RedisClientException
     */
    protected static function getReJsonClient(): RedisJsonClientInterface
    {
        $config = static::getReJsonClientConfig();
        $factory =  new RedisJsonClientFactory();

        return $factory->createClient($config);
    }

    protected static function storeData(string $key, array $data)
    {
        static::$reJsonClient->jsonSet($key, $data);
    }
}
