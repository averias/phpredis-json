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

class JsonArrayLengthCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonArrayLengthDataProvider
     * @param int|null $returnedValue
     * @param string $key
     * @param string $path
     */
    public function testJsonArrayLength(?int $returnedValue, string $key, string $path): void
    {
        $mock = $this->getRedisJsonClient($returnedValue, [JsonCommands::ARRAY_LENGTH, [$key], [$key, $path]]);
        $result = $mock->jsonArrayLength($key, $path);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonArrayLengthDataProvider(): array
    {
        return [
            [5, Keys::DEFAULT_KEY, '.path'],
            [null, Keys::DEFAULT_KEY, '.path']
        ];
    }
}
