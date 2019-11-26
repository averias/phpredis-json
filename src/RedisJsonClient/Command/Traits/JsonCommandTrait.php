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

use Averias\RedisJson\Encoder\Traits\JsonEncoderTrait;
use Averias\RedisJson\Enum\JsonCommands;
use Averias\RedisJson\Exception\ResponseException;

trait JsonCommandTrait
{
    use JsonEncoderTrait;

    /**
     * @param string $key
     * @param string $path
     * @return bool
     */
    public function jsonDelete(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::DELETE, [$key], [$key, $path]);
    }

    /**
     * @param string $key
     * @param string ...$paths
     * @return mixed|null
     */
    public function jsonGet(string $key, string ...$paths)
    {
        $paths = empty($paths) ? ['.'] : $paths;
        $params = array_merge([$key], $paths);

        return $this->executeJsonCommand(JsonCommands::GET, [$key], $params);
    }

    /**
     * @param array $keys
     * @param string $path
     * @return array
     */
    public function jsonMultiGet(array $keys, string $path = '.')
    {
        $params = array_merge($keys, [$path]);
        return  $this->executeJsonCommand(JsonCommands::MULTI_GET, $keys, $params);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $path
     * @param string|null $keyOptions
     * @return bool|null
     * @throws ResponseException
     */
    public function jsonSet(string $key, $value, string $path = '.', ?string $keyOptions = null)
    {
        $params = [$key, $path, $this->encode($value)];
        if (isset($keyOptions) && in_array($keyOptions, ['NX', 'XX'])) {
            $params[] = $keyOptions;
        }

        return $this->executeJsonCommand(JsonCommands::SET, [$key], $params);
    }

    /**
     * @param string $key
     * @param string $path
     * @return string|null
     */
    public function jsonType(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::TYPE, [$key], [$key, $path]);
    }

    /**
     * @param string $key
     * @param mixed $number
     * @param string $path
     * @return mixed
     */
    public function jsonIncrementNumBy(string $key, $number, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::INCREMENT_NUM_BY, [$key], [$key, $path, $number]);
    }

    /**
     * @param string $key
     * @param $number
     * @param string $path
     * @return mixed
     */
    public function jsonMultiplyNumBy(string $key, $number, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::MULTIPLY_NUM_BY, [$key], [$key, $path, $number]);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $path
     * @return int
     * @throws ResponseException
     */
    public function jsonStringAppend(string $key, string $value, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::APPEND_STRING, [$key], [$key, $path, $this->encode($value)]);
    }

    /**
     * @param string $key
     * @param string $path
     * @return int|null
     */
    public function jsonStringLength(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::STRING_LENGTH, [$key], [$key, $path]);
    }

    /**
     * @param string $key
     * @param string $path
     * @param mixed ...$values
     * @return int
     * @throws ResponseException
     */
    public function jsonArrayAppend(string $key, string $path, ...$values)
    {
        if (empty($values)) {
            throw new ResponseException(
                sprintf("you need to provide at least one value to append in %s command", JsonCommands::ARRAY_APPEND)
            );
        }

        $jsonValues = [];
        foreach ($values as $value) {
            $jsonValues[] = $this->encode($value);
        }

        $params = array_merge([$key, $path], $jsonValues);
        return $this->executeJsonCommand(JsonCommands::ARRAY_APPEND, [$key], $params);
    }

    /**
     * By providing 'start' and 'stop' params we get a slice from de original array.
     * if 'start' or 'stop' params are negative integers they will be counted from the end of the array so:
     * - if its absolute value for both is greater than the length of the array,
     *   that means: (abs(start|stop) > len(array), they will be set to 0
     *
     * - if 'stop' is positive and greater than the length of the array,
     *   it will be set to the value of the length of the array, stop = len(array)
     *
     * - if 'start' is positive and greater than the length of the array, the result will be an empty array
     *
     * - if 'start' > 'stop' the result will be an empty array
     *
     * @param string $key
     * @param string $value
     * @param string $path
     * @param int $start start position of the slice, default 0
     * @param int $stop end position of the slice (default 0 which means the end of the array - 0 counting from the end)
     * @return int
     * @throws ResponseException
     */
    public function jsonArrayIndex(string $key, string $value, string $path = '.', int $start = 0, int $stop = 0)
    {
        $params = [$key, $path, $this->encode($value), $start, $stop];
        return $this->executeJsonCommand(JsonCommands::ARRAY_INDEX, [$key], $params);
    }

    /**
     * @param string $key
     * @param string $path
     * @param int $index
     * @param mixed ...$values
     * @return int
     * @throws ResponseException
     */
    public function jsonArrayInsert(string $key, string $path, int $index, ...$values)
    {
        if (empty($values)) {
            throw new ResponseException(
                sprintf("you need to provide at least one value to insert in %s command", JsonCommands::ARRAY_INSERT)
            );
        }

        $jsonValues = [];
        foreach ($values as $value) {
            $jsonValues[] = $this->encode($value);
        }

        $params = array_merge([$key, $path, $index], $jsonValues);
        return $this->executeJsonCommand(JsonCommands::ARRAY_INSERT, [$key], $params);
    }

    /**
     * @param string $key
     * @param string $path
     * @return int|null
     */
    public function jsonArrayLength(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::ARRAY_LENGTH, [$key], [$key, $path]);
    }

    /**
     * @param string $key
     * @param string $path
     * @param int $index
     * @return mixed
     */
    public function jsonArrayPop(string $key, string $path = '.', int $index = -1)
    {
        return $this->executeJsonCommand(JsonCommands::ARRAY_POP, [$key], [$key, $path, $index]);
    }

    /**
     * if 'start' or 'stop' params are negative integers they will be counted from the end of the array so:
     * - if its absolute value for both is greater than the length of the array,
     *   that means: (abs(start|stop) > len(array), they will be set to 0, which will avoid to throw any error
     *
     * - if 'stop' is positive and greater than the length of the array,
     *   it will be set to the value of the length of the array, stop = len(array)
     *
     * - if 'start' is positive and greater than the length of the array, the result will be an empty array
     *
     * - if 'start' > 'stop' the result will be an empty array
     *
     * - if start or stop are equal to -1 it will considered at the last position in the array, value of -2 is the
     *   second-to-last position and so on
     *
     * The above explanation was necessary since the documentation is not clear about 'start' and 'stop' params
     * in @link https://oss.redislabs.com/redisjson/commands/#jsonarrtrim
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @param string $path
     * @return int
     */
    public function jsonArrayTrim(string $key, int $start, int $stop, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::ARRAY_TRIM, [$key], [$key, $path, $start, $stop]);
    }

    /**
     * @param string $key
     * @param string $path
     * @return array|null
     */
    public function jsonObjectKeys(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::OBJECT_KEYS, [$key], [$key, $path]);
    }

    /**
     * @param string $key
     * @param string $path
     * @return int|null
     */
    public function jsonObjectLength(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::OBJECT_LENGTH, [$key], [$key, $path]);
    }

    /**
     * @param $key
     * @param string $path
     * @return int|null
     */
    public function jsonMemoryUsage(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::MEMORY_USAGE, [$key], ['MEMORY', $key, $path]);
    }

    /**
     * @param string $key
     * @param string $path
     * @return bool
     */
    public function jsonForget(string $key, string $path = '.')
    {
        return $this->jsonDelete($key, $path);
    }

    /**
     * @param string $key
     * @param string $path
     * @return mixed
     */
    public function jsonGetAsResp(string $key, string $path = '.')
    {
        return $this->executeJsonCommand(JsonCommands::GET_AS_RESP, [$key], [$key, $path]);
    }
}
