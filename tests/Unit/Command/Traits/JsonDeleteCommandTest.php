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

class JsonDeleteCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonDeleteDataProvider
     * @param int $returnedValue
     * @param bool $resultValue
     */
    public function testJsonDelete(?int $returnedValue, bool $resultValue)
    {
        $arguments = [JsonCommands::DELETE, [Keys::DEFAULT_KEY], [Keys::DEFAULT_KEY, '.']];
        $mock = $this->getRedisJsonClient($returnedValue, $arguments);
        $result = $mock->jsonDelete(Keys::DEFAULT_KEY, '.');
        $this->assertEquals($resultValue, $result);
    }

    public function getJsonDeleteDataProvider(): array
    {
        return [
            [0, false],
            [1, true],
            [null, false]
        ];
    }
}
