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

class JsonArrayTrimCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonArrayTrimDataProvider
     * @param int|null $returnedValue
     * @param string $key
     * @param string $path
     * @param int $start
     * @param int $stop
     */
    public function testJsonArrayTrim(?int $returnedValue, string $key, string $path, int $start, int $stop): void
    {
        $mock = $this->getRedisJsonClient(
            $returnedValue,
            [JsonCommands::ARRAY_TRIM, [$key], [$key, $path, $start, $stop]]
        );
        $result = $mock->jsonArrayTrim($key, $start, $stop, $path);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonArrayTrimDataProvider(): array
    {
        return [
            [3, Keys::DEFAULT_KEY, '.path', 1, 3],
            [0, Keys::DEFAULT_KEY, '.path', 3, 1]
        ];
    }
}
