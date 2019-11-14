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

namespace Averias\RedisJson\Factory;

use Averias\RedisJson\Adapter\AdapterProvider;
use Averias\RedisJson\Client\RedisJsonClient;
use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Exception\RedisClientException;
use Averias\RedisJson\Validator\RedisClientValidator;
use Exception;

class RedisJsonClientFactory implements RedisJsonClientFactoryInterface
{
    /** @var AdapterProvider */
    protected $adapterProvider;

    public function __construct()
    {
        $this->adapterProvider = new AdapterProvider(new RedisClientValidator());
    }

    /**
     * @param array|null $config
     * @return RedisJsonClientInterface
     * @throws RedisClientException
     */
    public function createClient(array $config = []): RedisJsonClientInterface
    {
        try {
            $adapter = $this->adapterProvider->get($config);
        } catch (Exception $e) {
            throw new RedisClientException($e->getMessage());
        }

        return new RedisJsonClient($adapter);
    }
}
