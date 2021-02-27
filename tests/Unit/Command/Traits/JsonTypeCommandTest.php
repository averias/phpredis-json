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

class JsonTypeCommandTest extends BaseTestJsonCommandTrait
{
    /**
     * @dataProvider getJsonTypeDataProvider
     * @param string $returnedValue
     */
    public function testJsonType(?string $returnedValue): void
    {
        $params = [Keys::DEFAULT_KEY, '.'];
        $mock = $this->getRedisJsonClient($returnedValue, [JsonCommands::TYPE, [Keys::DEFAULT_KEY], $params]);
        $result = $mock->jsonType(...$params);
        $this->assertEquals($returnedValue, $result);
    }

    public function getJsonTypeDataProvider(): array
    {
        return [
            ['object'],
            ['string'],
            ['integer'],
            ['number'],
            ['object'],
            ['array'],
            ['boolean'],
            [null]
        ];
    }
}
