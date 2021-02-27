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

class JsonMultiplyNumByCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonMultiplyNumByDataProvider
     * @param int $returnedValue
     * @param string $key
     * @param $number
     * @param string $path
     * @param bool $resultValue
     */
    public function testJsonMultiplyNumBy($returnedValue, string $key, $number, string $path, $resultValue): void
    {
        $arguments = [JsonCommands::MULTIPLY_NUM_BY, [$key], [$key, $path, $number]];
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonMultiplyNumBy($key, $number, $path);
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonMultiplyNumByDataProvider(): array
    {
        return [
            ['200', Keys::DEFAULT_KEY, 10, '.', 200],
            ['10.79', Keys::DEFAULT_KEY, 10, '.', 10.79]
        ];
    }
}
