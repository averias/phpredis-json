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

class JsonArrayIndexCommandTest extends BaseTestJsonCommandTrait
{
    public function testJsonArrayIndex(): void
    {
        $mock = $this->getRedisJsonClient(
            3,
            [JsonCommands::ARRAY_INDEX, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.', '"value"', 0, 0]]
        );
        $result = $mock->jsonArrayIndex(Keys::DEFAULT_KEY, 'value', '.');
        $this->assertEquals(3, $result);
    }
}
