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

class JsonMemoryUsageCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonMemoryUsageDataProvider
     * @param int|null $returnedValue
     * @param string $key
     * @param string $path
     */
    public function testJsonMemoryUsage(?int $returnedValue, string $key, string $path): void
    {
        $arguments = [JsonCommands::MEMORY_USAGE, [$key], ['MEMORY', $key, $path]];
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonMemoryUsage($key, $path);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonMemoryUsageDataProvider(): array
    {
        return [
            [7, Keys::DEFAULT_KEY, '.'],
            [null, Keys::DEFAULT_KEY, '.path']
        ];
    }
}
