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

namespace Averias\RedisJson\Adapter;

use Averias\RedisJson\Exception\InvalidRedisVersionException;
use Averias\RedisJson\Exception\RedisJsonModuleNotInstalledException;
use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Connection\ConnectionOptions;
use Averias\RedisJson\Exception\ConnectionException;
use Averias\RedisJson\Validator\RedisClientValidatorInterface;
use Redis;

class AdapterProvider
{
    /** @var RedisClientValidatorInterface */
    protected $validator;

    public function __construct(RedisClientValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array|null $config
     * @return RedisClientAdapterInterface
     * @throws ConnectionException
     * @throws InvalidRedisVersionException
     * @throws RedisJsonModuleNotInstalledException
     * @throws ResponseException
     */
    public function get(array $config = []): RedisClientAdapterInterface
    {
        $redisClient = $this->getRedisClient($config);

        $redisInfo = $redisClient->executeCommandByName('INFO', []);
        $redisServerVersion = $redisInfo['redis_version'];
        if (!$this->validator->isValidRedisVersion($redisServerVersion)) {
            throw new InvalidRedisVersionException(
                sprintf('invalid redis server version %s, expected 4.0+.', $redisServerVersion)
            );
        }

        $moduleS = $redisClient->executeRawCommand('MODULE', 'list');
        if (!$this->validator->isRedisJsonModuleInstalled($moduleS)) {
            throw new RedisJsonModuleNotInstalledException('RedisJson module not installed in Redis server.');
        }

        return $redisClient;
    }

    /**
     * @param array $config
     * @return RedisClientAdapterInterface
     * @throws ConnectionException
     */
    protected function getRedisClient(array $config = []): RedisClientAdapterInterface
    {
        $connectionOptions = new ConnectionOptions($config);
        return new RedisClientAdapter(new Redis(), $connectionOptions);
    }
}
