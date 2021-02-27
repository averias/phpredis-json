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

class JsonObjectKeysCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonObjectKeysDataProvider
     * @param array $returnedValue
     * @param string $key
     * @param string $path
     */
    public function testJsonObjectKeys(?array $returnedValue, string $key, string $path): void
    {
        $mock = $this->getRedisJsonClient($returnedValue, [JsonCommands::OBJECT_KEYS, [$key], [$key, $path]]);
        $result = $mock->jsonObjectKeys($key, $path);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonObjectKeysDataProvider(): array
    {
        return [
            [['key1', 'key2'], Keys::DEFAULT_KEY, '.'],
            [null, Keys::DEFAULT_KEY, '.path']
        ];
    }
}
