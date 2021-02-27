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

class JsonGetCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonGetDataProvider
     * @param array $arguments
     * @param int $returnedValue
     * @param string $key
     * @param array $paths
     * @param bool $resultValue
     */
    public function testJsonGet(array $arguments, $returnedValue, string $key, array $paths, $resultValue): void
    {
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonGet($key, ...$paths);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonGetDataProvider(): array
    {
        $value = '{"name":"foo"}';
        return [
            [
                [JsonCommands::GET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1']],
                '{".path1":' . $value . '}',
                Keys::DEFAULT_KEY,
                ['.path1'],
                '{".path1":' . $value . '}'
            ],
            [
                [JsonCommands::GET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.path1', '.path2']],
                '{".path1":' . $value . ',".path2":' . $value . '}',
                Keys::DEFAULT_KEY,
                ['.path1', '.path2'],
                '{".path1":' . $value . ',".path2":' . $value . '}'
            ],
            [
                [JsonCommands::GET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.']],
                '{".":' . $value . '}',
                Keys::DEFAULT_KEY,
                [],
                '{".":' . $value . '}'
            ],
            [
                [JsonCommands::GET, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.']],
                null,
                Keys::DEFAULT_KEY,
                [],
                null
            ]
        ];
    }
}
