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

declare(strict_types=1);

namespace Averias\RedisJson\Connection;

use Averias\RedisJson\Enum\Connection;
use Averias\RedisJson\Exception\ConnectionOptionsException;

class ConnectionOptions
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var float */
    private $timeout;

    /** @var int */
    private $retryInterval;

    /** @var float */
    private $readTimeout;

    /** @var string */
    private $persistentId;

    /** @var int */
    private $database;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $host = $config[Connection::HOST] ?? Connection::DEFAULT[Connection::HOST];
        $this->setHost($host);

        $port = $config[Connection::PORT] ?? Connection::DEFAULT[Connection::PORT];
        $this->setPort($port);

        $timeout = $config[Connection::TIMEOUT] ?? Connection::DEFAULT[Connection::TIMEOUT];
        $this->setTimeout($timeout);

        $retryInterval = $config[Connection::RETRY_INTERVAL] ?? Connection::DEFAULT[Connection::RETRY_INTERVAL];
        $this->setRetryInterval($retryInterval);

        $readTimeout = $config[Connection::READ_TIMEOUT] ?? Connection::DEFAULT[Connection::READ_TIMEOUT];
        $this->setReadTimeout($readTimeout);

        $persistent = $config[Connection::PERSISTENCE_ID] ?? Connection::DEFAULT[Connection::PERSISTENCE_ID];
        $this->setPersistentId($persistent);

        $database = $config[Connection::DATABASE] ?? Connection::DEFAULT[Connection::DATABASE];
        $this->setDatabase($database);
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return ConnectionOptions
     */
    public function setHost(string $host): ConnectionOptions
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return ConnectionOptions
     */
    public function setPort(int $port): ConnectionOptions
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return float in seconds
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @param float $timeout in seconds
     * @return ConnectionOptions
     */
    public function setTimeout(float $timeout): ConnectionOptions
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return int in milliseconds
     */
    public function getRetryInterval(): int
    {
        return $this->retryInterval;
    }

    /**
     * @param int $retryInterval in milliseconds
     * @return ConnectionOptions
     */
    public function setRetryInterval(int $retryInterval): ConnectionOptions
    {
        $this->retryInterval = $retryInterval;
        return $this;
    }

    /**
     * @return float in seconds
     */
    public function getReadTimeout(): float
    {
        return $this->readTimeout;
    }

    /**
     * @param float $readTimeout in seconds
     * @return ConnectionOptions
     */
    public function setReadTimeout(float $readTimeout): ConnectionOptions
    {
        $this->readTimeout = $readTimeout;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPersistent(): bool
    {
        return !is_null($this->persistentId);
    }

    /**
     * @return int Redis database index [0..15]
     */
    public function getDatabase(): int
    {
        return $this->database;
    }

    /**
     * @param int $database Redis database index [0..15]
     * @return ConnectionOptions
     */
    public function setDatabase(int $database): ConnectionOptions
    {
        if ($database < 0 || $database > 15) {
            throw new ConnectionOptionsException(
                sprintf("redis database value out of range, expected: 0-15, assigned: %d", $database)
            );
        }
        $this->database = $database;
        return $this;
    }

    /**
     * @return string|null identity for the requested persistent connection or null for non persistent connection
     */
    public function getPersistentId(): ?string
    {
        return $this->persistentId;
    }

    /**
     * @param string|null $persistentId identity for the persistent connection or null for non persistent connection
     * @return ConnectionOptions
     */
    public function setPersistentId(?string $persistentId): ConnectionOptions
    {
        $this->persistentId = $persistentId;
        return $this;
    }

    public function getConnectionValues(): array
    {
        return [
            $this->getHost(),
            $this->getPort(),
            $this->getTimeout(),
            $this->getPersistentId(),
            $this->getRetryInterval(),
            $this->getReadTimeout()
        ];
    }
}
