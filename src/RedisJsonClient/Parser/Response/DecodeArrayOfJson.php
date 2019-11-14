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

class DecodeArrayOfJson extends BaseJsonDecoderParser implements ParserInterface
{
    /**
     * @param array $response
     * @return array
     * @throws ResponseException
     */
    public function parse($response)
    {
        if (!is_array($response)) {
            throw new ResponseException(sprintf("expected array response but got '%s'", gettype($response)));
        }

        $decodedResult = [];
        foreach ($response as $value) {
            $decodedResult[] = $this->decodeIfNotFalse($value);
        }

        return $decodedResult;
    }
}
