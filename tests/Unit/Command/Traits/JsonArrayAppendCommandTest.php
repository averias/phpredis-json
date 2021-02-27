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

namespace Averias\RedisJson\Tests\Unit\Command\Traits;

use Averias\RedisJson\Enum\JsonCommands;
use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Enum\Keys;
use Averias\RedisJson\Tests\Unit\Command\BaseTestJsonCommandTrait;

class JsonArrayAppendCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonArrayAppendDataProvider
     * @param array $arguments
     * @param int $returnedValue
     * @param string $key
     * @param string $path
     * @param array $values
     */
    public function testJsonArrayAppend(
        array $arguments,
        int $returnedValue,
        string $key,
        string $path,
        array $values
    ): void {
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonArrayAppend($key, $path, ...$values);
        $this->assertEquals($returnedValue, $result);
    }

    public function testJsonArrayAppendException(): void
    {
        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage('you need to provide at least one value to append in JSON.ARRAPPEND command');
        $mock = $this->getRedisJsonClient(
            1,
            [JsonCommands::ARRAY_APPEND, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.']]
        );
        $mock->jsonArrayAppend(Keys::DEFAULT_KEY, '.', ...[]);
    }

    public function getJsonArrayAppendDataProvider(): array
    {
        return [
            [
                [JsonCommands::ARRAY_APPEND, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', '"value1"', '14.3']],
                3,
                Keys::DEFAULT_KEY,
                '.',
                ['value1', 14.3]
            ],
            [
                [JsonCommands::ARRAY_APPEND, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', '{"name":"foo"}', 'true']],
                6,
                Keys::DEFAULT_KEY,
                '.',
                [["name" => "foo"], true]
            ]
        ];
    }
}
