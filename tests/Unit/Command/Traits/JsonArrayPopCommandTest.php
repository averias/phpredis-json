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
use Averias\RedisJson\Enum\Keys;
use Averias\RedisJson\Tests\Unit\Command\BaseTestJsonCommandTrait;

class JsonArrayPopCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonArrayPopDataProvider
     * @param array $arguments
     * @param int $returnedValue
     * @param string $key
     * @param string $path
     * @param bool $resultValue
     */
    public function testJsonArrayPop(
        array $arguments,
        $returnedValue,
        string $key,
        string $path,
        $resultValue
    ): void {
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonArrayPop($key, $path);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonArrayPopDataProvider(): array
    {
        return [
            [
                [JsonCommands::ARRAY_POP, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', -1]],
                '{"name":"foo"}',
                Keys::DEFAULT_KEY,
                '.',
                '{"name":"foo"}'
            ],
            [
                [JsonCommands::ARRAY_POP, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', -1]],
                null,
                Keys::DEFAULT_KEY,
                '.',
                null
            ]
        ];
    }
}
