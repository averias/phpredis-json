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

use Averias\RedisJson\Exception\ResponseException;

interface RedisClientAdapterInterface
{
    /**
     * @param string $command
     * @param array $keys
     * @param array $params
     * @return mixed
     * @throws ResponseException
     */
    public function executeJsonCommand(string $command, array $keys, array $params);

    /**
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     * @throws ResponseException
     */
    public function executeCommandByName(string $methodName, array $arguments);

    /**
     * @param string $commandName
     * @param array $arguments
     * @return mixed
     */
    public function executeRawCommand(string $commandName, ...$arguments);
}
