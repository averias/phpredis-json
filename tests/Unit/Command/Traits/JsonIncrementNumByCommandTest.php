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

class JsonIncrementNumByCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonIncrementNumByDataProvider
     * @param int $returnedValue
     * @param string $key
     * @param $number
     * @param string $path
     * @param bool $resultValue
     */
    public function testJsonIncrementNumBy($returnedValue, string $key, $number, string $path, $resultValue)
    {
        $arguments = [JsonCommands::INCREMENT_NUM_BY, [$key], [$key, $path, $number]];
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonIncrementNumBy($key, $number, $path);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonIncrementNumByDataProvider(): array
    {
        return [
            ['20', Keys::DEFAULT_KEY, 10, '.', 20],
            ['11.79', Keys::DEFAULT_KEY, 10, '.', 11.79]
        ];
    }
}
