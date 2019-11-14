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
use Averias\RedisJson\Tests\Unit\Command\BaseTestJsonCommandTrait;

class JsonMultiGetCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonMultiGetDataProvider
     * @param int $returnedValue
     * @param array $keys
     * @param string $path
     * @param bool $resultValue
     */
    public function testJsonMultiGet($returnedValue, array $keys, string $path, $resultValue): void
    {
        $arguments = [JsonCommands::MULTI_GET, $keys, array_merge($keys, [$path])];
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonMultiGet($keys, $path);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonMultiGetDataProvider(): array
    {
        $value = '{"name":"foo"}';
        return [
            [
                [$value, $value],
                ['test-key1', 'test-key2'],
                '.path1',
                [$value, $value]
            ],
            [
                [$value, null],
                ['test-key1', 'nonexistent-key'],
                '.path1',
                [$value, null]
            ],
            [
                [$value],
                ['test-key1'],
                '.path1',
                [$value]
            ],
            [
                [],
                [],
                '.',
                []
            ]
        ];
    }
}
