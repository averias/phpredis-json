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

namespace Averias\RedisJson\Parser\Response;

use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Parser\ParserInterface;

class OkToTrue implements ParserInterface
{
    /**
     * @param $response
     * @return bool
     * @throws ResponseException
     */
    public function parse($response)
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
