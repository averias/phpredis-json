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

namespace Averias\RedisJson\Enum;

use Averias\RedisJson\Parser\Response\DecodeArrayOfJson;
use Averias\RedisJson\Parser\Response\DecodeFromJson;
use Averias\RedisJson\Parser\Response\IntegerToBoolean;
use Averias\RedisJson\Parser\Response\OkToTrue;
use MyCLabs\Enum\Enum;

class ResponseParser extends Enum
{
    const RESPONSE_PARSER = [
        JsonCommands::DELETE => IntegerToBoolean::class,
        JsonCommands::GET => DecodeFromJson::class,
        JsonCommands::MULTI_GET => DecodeArrayOfJson::class,
        JsonCommands::INCREMENT_NUM_BY => DecodeFromJson::class,
        JsonCommands::MULTIPLY_NUM_BY => DecodeFromJson::class,
        JsonCommands::ARRAY_POP => DecodeFromJson::class,
        JsonCommands::SET => OkToTrue::class
    ];
}
