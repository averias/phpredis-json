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

namespace Averias\RedisJson\Client;

use Averias\RedisJson\Adapter\RedisClientAdapterInterface;
use Averias\RedisJson\Command\Traits\JsonCommandTrait;
use Averias\RedisJson\Exception\ResponseException;

class RedisJsonClient implements RedisJsonClientInterface
{
    use JsonCommandTrait;

    /** @var RedisClientAdapterInterface */
    private $redisClientAdapter;

    public function __construct(RedisClientAdapterInterface $redisClientAdapter)
    {
        $this->redisClientAdapter = $redisClientAdapter;
    }

    /**
     * @param string $commandName
     * @param array $arguments
     * @return mixed
     */
    public function executeRawCommand(string $commandName, ...$arguments)
    {
        return $this->redisClientAdapter->executeRawCommand($commandName, ...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ResponseException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->redisClientAdapter->executeCommandByName($name, $arguments);
    }

    /**
     * @param string $command
     * @param array $keys
     * @param array $params
     * @return mixed
     * @throws ResponseException
     */
    protected function executeJsonCommand(string $command, array $keys, array $params = [])
    {
        return $this->redisClientAdapter->executeJsonCommand($command, $keys, $params);
    }
}
