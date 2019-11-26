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

use MyCLabs\Enum\Enum;

class JsonCommands extends Enum
{
    /** Commands  */
    const DELETE = 'JSON.DEL';
    const GET = 'JSON.GET';
    const SET = 'JSON.SET';
    const MULTI_GET = 'JSON.MGET';
    const TYPE = 'JSON.TYPE';
    const INCREMENT_NUM_BY = 'JSON.NUMINCRBY';
    const MULTIPLY_NUM_BY = 'JSON.NUMMULTBY';
    const APPEND_STRING = 'JSON.STRAPPEND';
    const STRING_LENGTH = 'JSON.STRLEN';
    const ARRAY_APPEND = 'JSON.ARRAPPEND';
    const ARRAY_INDEX = 'JSON.ARRINDEX';
    const ARRAY_INSERT = 'JSON.ARRINSERT';
    const ARRAY_LENGTH = 'JSON.ARRLEN';
    const ARRAY_POP = 'JSON.ARRPOP';
    const ARRAY_TRIM = 'JSON.ARRTRIM';
    const OBJECT_KEYS = 'JSON.OBJKEYS';
    const OBJECT_LENGTH = 'JSON.OBJLEN';
    const MEMORY_USAGE = 'JSON.DEBUG';
    const GET_AS_RESP = 'JSON.RESP';

    /** Messages  */
    const DEFAULT_MESSAGE = 'key or path does not exist';
    const EXCEPTION_MESSAGES = [
        self::DELETE => 'JSON.DEL',
        self::GET => self::DEFAULT_MESSAGE,
        self::SET => 'NX or NX conditions were not met, no root path for new keys, ...',
        self::MULTI_GET => 'JSON.MGET',
        self::TYPE => self::DEFAULT_MESSAGE,
        self::INCREMENT_NUM_BY => self::DEFAULT_MESSAGE . ', increment a no number value, ...',
        self::MULTIPLY_NUM_BY => self::DEFAULT_MESSAGE . ', multiply a no number value, ...',
        self::APPEND_STRING => self::DEFAULT_MESSAGE . ', append to no string value, ...',
        self::STRING_LENGTH => self::DEFAULT_MESSAGE . ', length of no string value, ...',
        self::ARRAY_APPEND => self::DEFAULT_MESSAGE . ', append to no array, ...',
        self::ARRAY_INDEX => self::DEFAULT_MESSAGE . ', index on no array, ...',
        self::ARRAY_INSERT => self::DEFAULT_MESSAGE . ', insertion on no array, ...',
        self::ARRAY_LENGTH => self::DEFAULT_MESSAGE . ', length of no array, ...',
        self::ARRAY_POP => self::DEFAULT_MESSAGE . ', empty array, no array, ...',
        self::ARRAY_TRIM => self::DEFAULT_MESSAGE . ', trimming no array, ...',
        self::OBJECT_KEYS => self::DEFAULT_MESSAGE . ', no JSON object, ...',
        self::OBJECT_LENGTH => self::DEFAULT_MESSAGE . ', empty object, no object, ...',
        self::MEMORY_USAGE => self::DEFAULT_MESSAGE,
        self::GET_AS_RESP => self::DEFAULT_MESSAGE
    ];
}
