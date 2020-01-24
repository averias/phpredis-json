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

namespace Averias\RedisJson\Command\Traits;

interface JsonCommandTraitInterface
{
    public function jsonDelete(string $key, string $path = '.');

    public function jsonGet(string $key, string ...$paths);

    public function jsonMultiGet(array $keys, string $path = '.');

    public function jsonSet(string $key, $value, string $path = '.', ?string $keyOptions = null);

    public function jsonType(string $key, string $path = '.');

    public function jsonIncrementNumBy(string $key, $number, string $path = '.');

    public function jsonMultiplyNumBy(string $key, $number, string $path = '.');

    public function jsonStringAppend(string $key, string $value, string $path = '.');

    public function jsonStringLength(string $key, string $path = '.');

    public function jsonArrayAppend(string $key, string $path, ...$values);

    public function jsonArrayIndex(string $key, string $value, string $path = '.', int $start = 0, int $stop = 0);

    public function jsonArrayInsert(string $key, string $path, int $index, ...$values);

    public function jsonArrayLength(string $key, string $path = '.');

    public function jsonArrayPop(string $key, string $path = '.', int $index = -1);

    public function jsonArrayTrim(string $key, int $start, int $stop, string $path = '.');

    public function jsonObjectKeys(string $key, string $path = '.');

    public function jsonObjectLength(string $key, string $path = '.');

    public function jsonMemoryUsage(string $key, string $path = '.');

    public function jsonForget(string $key, string $path = '.');

    public function jsonGetAsResp(string $key, string $path = '.');
}
