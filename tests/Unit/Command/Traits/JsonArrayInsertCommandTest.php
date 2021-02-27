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

class JsonArrayInsertCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonArrayInsertDataProvider
     * @param array $arguments
     * @param int $returnedValue
     * @param string $key
     * @param string $path
     * @param int $index
     * @param array $values
     */
    public function testJsonInsertAppend(
        array $arguments,
        int $returnedValue,
        string $key,
        string $path,
        int $index,
        array $values
    ): void {
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonArrayInsert($key, $path, $index, ...$values);
        $this->assertEquals($returnedValue, $result);
    }

    public function testJsonArrayInsertException(): void
    {
        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage('you need to provide at least one value to insert in JSON.ARRINSERT command');
        $mock = $this->getRedisJsonClient(
            5,
            [JsonCommands::ARRAY_INSERT, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', 1]]
        );
        $mock->jsonArrayInsert(Keys::DEFAULT_KEY, '.', 1, ...[]);
    }

    public function getJsonArrayInsertDataProvider(): array
    {
        return [
            [
                [JsonCommands::ARRAY_INSERT, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', 3, '"value1"', '14.3']],
                5,
                Keys::DEFAULT_KEY,
                '.',
                3,
                ['value1', 14.3]
            ],
            [
                [JsonCommands::ARRAY_INSERT, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', 2, '{"name":"foo"}', 'true']],
                4,
                Keys::DEFAULT_KEY,
                '.',
                2,
                [["name" => "foo"], true]
            ]
        ];
    }
}
