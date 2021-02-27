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

class JsonGetAsRespCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonGetAsRespDataProvider
     * @param mixed $returnedValue
     * @param string $key
     * @param string $path
     */
    public function testJsonGetAsResp($returnedValue, string $key, string $path): void
    {
        $mock = $this->getRedisJsonClient($returnedValue, [JsonCommands::GET_AS_RESP, [$key], [$key, $path]]);
        $result = $mock->jsonGetAsResp($key, $path);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonGetAsRespDataProvider(): array
    {
        return [
            [['[', 'value1', 23, ']'], Keys::DEFAULT_KEY, '.'],
            ['Peter', Keys::DEFAULT_KEY, '.'],
            [56, Keys::DEFAULT_KEY, '.'],
            [null, Keys::DEFAULT_KEY, '.']
        ];
    }
}
