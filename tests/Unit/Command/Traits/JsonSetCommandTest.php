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

class JsonSetCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonSetDataProvider
     * @param array $arguments
     * @param bool|null $returnedValue
     * @param string $key
     * @param $value
     * @param string $path
     * @param string|null $keyOptions
     * @param bool $resultValue
     */
    public function testJsonSet(
        array $arguments,
        ?bool $returnedValue,
        string $key,
        $value,
        string $path,
        ?string $keyOptions,
        ?bool $resultValue
    ): void {
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonSet($key, $value, $path, $keyOptions);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonSetDataProvider(): array
    {
        $value = ["name" => "foo"];
        return [
            [
                [JsonCommands::SET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1', '{"name":"foo"}']],
                true,
                Keys::DEFAULT_KEY,
                $value,
                '.path1',
                null,
                true
            ],
            [
                [JsonCommands::SET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1', '{"name":"foo"}', 'NX']],
                null,
                Keys::DEFAULT_KEY,
                $value,
                '.path1',
                'NX',
                null
            ],
            [
                [JsonCommands::SET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1', '{"name":"foo"}', 'XX']],
                null,
                Keys::DEFAULT_KEY,
                $value,
                '.path1',
                'XX',
                null
            ],
            [
                [JsonCommands::SET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1', '{"name":"foo"}']],
                null,
                Keys::DEFAULT_KEY,
                $value,
                '.path1',
                'AX',
                null
            ]
        ];
    }
}
