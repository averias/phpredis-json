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

namespace Averias\RedisJson\Encoder\Traits;

use Averias\RedisJson\Exception\ResponseException;

trait JsonEncoderTrait
{
    /**
     * @param $value
     * @return string
     * @throws ResponseException
     */
    public function encode($value): string
    {
        // @TODO: when update to PHP 7.3+ wrap it with a try...catch for capturing \JsonException
        // as explained here https://ayesh.me/Upgrade-PHP-7.3#json-exceptions
        $jsonEncodedValue = json_encode($value);
        if (false === $jsonEncodedValue || json_last_error() !== JSON_ERROR_NONE) {
            throw new ResponseException(
                sprintf('value could not be json-encoded properly, reason: %s', json_last_error_msg())
            );
        }

        return $jsonEncodedValue;
    }

    /**
     * @param string $json
     * @return mixed
     * @throws ResponseException
     */
    public function decode(string $json)
    {
        // @TODO: when update to PHP 7.3+ wrap it with a try...catch for capturing \JsonException
        // as explained here https://ayesh.me/Upgrade-PHP-7.3#json-exceptions
        $decodedValue = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ResponseException(
                sprintf('"%s" could not be json-decoded properly, reason: %s', $json, json_last_error_msg())
            );
        }

        return $decodedValue;
    }
}
