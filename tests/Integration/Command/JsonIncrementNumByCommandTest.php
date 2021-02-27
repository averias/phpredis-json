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

class JsonIncrementNumByCommandTest extends BaseTestIntegration
{
    protected static $data = [
        'integerVal' => 38,
        'floatVal' => 1.79,
        'integerValAsString' => '56',
        'floatValAsString' => '34.7',
        'stringValStartingByInteger' => '3abc',
        'stringValStartingByFloat' => '3.2abc'
    ];

    public static function setUpBeforeClass(): void
    {
        static::$reJsonClient  = self::getReJsonClient();
        static::storeData(Keys::DEFAULT_KEY, self::$data);
    }

    public function testIncrementInteger()
    {
        $this->assertEquals(40, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.integerVal'));
        $this->assertEquals(42.7, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2.7, '.integerVal'));
    }

    public function testIncrementFloat()
    {
        $this->assertEquals(2.89, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 1.1, '.floatVal'));
        $this->assertEquals(3.89, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 1, '.floatVal'));
    }

    public function testIncrementByIntegerString()
    {
        $this->assertEquals(4.89, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, '1', '.floatVal'));
    }

    public function testIncrementByFloatString()
    {
        $this->assertEquals(44.0, static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, '1.3', '.integerVal'));
    }

    public function testIncrementByInvalidNumberAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, '1a', '.floatVal');
    }

    public function testIncrementIntegerAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.integerValAsString');
    }

    public function testIncrementFloatAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.floatValAsString');
    }

    public function testIncrementStringStartingByIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.stringValStartingByInteger');
    }

    public function testIncrementFloatStartingByFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.stringValStartingByFloat');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy('nonexistent', 2);
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonIncrementNumBy(Keys::DEFAULT_KEY, 2, '.nonexistent');
    }
}
