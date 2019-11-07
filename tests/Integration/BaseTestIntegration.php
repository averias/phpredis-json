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

namespace Averias\RedisJson\Tests\Integration;

use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Exception\RedisClientException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use Averias\RedisJson\Tests\Enum\Keys;
use PHPUnit\Framework\TestCase;

class BaseTestIntegration extends TestCase
{
    protected static $defaultData = [
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
    public static function setUpBeforeClass()
    {
        static::$reJsonClient  = self::getReJsonClient();
        static::storeData(Keys::DEFAULT_KEY, static::$defaultData);
    }

    public static function tearDownAfterClass()
    {
        if (static::$reJsonClient) {
            static::$reJsonClient->select(0);
            static::$reJsonClient->flushall();
        }
    }

    /**
     * @param array $data
     * @throws RedisClientException
     */
    public static function loadDataBeforeClass(array $data)
    {
        static::setUpBeforeClass();
        static::storeData(Keys::DEFAULT_KEY, $data);
    }

    protected static function getReJsonClientConfig()
    {
        return [
            'server' => REDIS_TEST_SERVER,
            'timeout' => 2,
            'version' => REDIS_TEST_VERSION
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
