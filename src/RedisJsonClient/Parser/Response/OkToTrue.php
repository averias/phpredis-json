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

namespace Averias\RedisJson\Parser\Response;

use Averias\RedisJson\Exception\ResponseException;

class OkToTrue
{
    /**
     * @param $response
     * @return bool
     * @throws ResponseException
     */
    public function parse($response): bool
    {
        if (!is_string($response)) {
            throw new ResponseException(sprintf("expected string response but got '%s'", gettype($response)));
        }

        if ($response !== 'OK') {
            throw new ResponseException(sprintf("expected 'OK' string response but got '%s'", $response));
        }

        return true;
    }
}
