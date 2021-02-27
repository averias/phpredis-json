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

namespace Averias\RedisJson\Tests\Integration\Command;

use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Enum\Keys;
use Averias\RedisJson\Tests\Integration\BaseTestIntegration;

class JsonTypeCommandTest extends BaseTestIntegration
{
    /**
     * @dataProvider getTypesDataProvider
     * @param string $path
     * @param string $expectedType
     */
    public function testType(string $path, string $expectedType)
    {
        $type = static::$reJsonClient->jsonType(Keys::DEFAULT_KEY, $path);
        $this->assertSame($expectedType, $type);
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonType('nonexistent');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonType(Keys::DEFAULT_KEY, '.nonexistent');
    }

    public function getTypesDataProvider()
    {
        return [
            ['.', 'object'],
            ['.name', 'string'],
            ['.age', 'integer'],
            ['.height', 'number'],
            ['.location', 'object'],
            ['.colors', 'array'],
            ['.license', 'boolean']
        ];
    }
}
