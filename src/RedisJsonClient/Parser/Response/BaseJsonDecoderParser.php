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

use Averias\RedisJson\Encoder\Traits\JsonEncoderTrait;
use Averias\RedisJson\Exception\ResponseException;

class BaseJsonDecoderParser
{
    use JsonEncoderTrait;

    /**
     * @param string|bool $response
     * @return mixed
     * @throws ResponseException
     */
    public function decodeIfNotFalse($response)
    {
        if ($response === false) {
            return null;
        }

        return $this->decode($response);
    }
}
