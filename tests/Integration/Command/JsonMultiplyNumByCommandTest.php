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

class JsonMultiplyNumByCommandTest extends BaseTestIntegration
{
    protected static $data = [
        'integerVal' => 38,
        'floatVal' => 1.79,
        'integerValAsString' => '56',
        'floatValAsString' => '34.7',
        'stringValStartingByInteger' => '3abc',
        'stringValStartingByFloat' => '3abc'
    ];

    public static function setUpBeforeClass(): void
    {
        static::$reJsonClient  = self::getReJsonClient();
        static::storeData(Keys::DEFAULT_KEY, self::$data);
    }

    public function testMultiplyInteger()
    {
        $this->assertEquals(76, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.integerVal'));
        $this->assertEquals(190, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2.5, '.integerVal'));
    }

    public function testMultiplyFloat()
    {
        $this->assertEquals(1.969, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 1.1, '.floatVal'));
        $this->assertEquals(3.938, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.floatVal'));
    }

    public function testMultiplyByIntegerString()
    {
        $this->assertEquals(7.876, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, '2', '.floatVal'));
    }

    public function testIncrementByFloatString()
    {
        $this->assertEquals(285.0, static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, '1.5', '.integerVal'));
    }

    public function testMultiplyByInvalidNumberAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, '1a', '.floatVal');
    }

    public function testMultiplyIntegerAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.integerValAsString');
    }

    public function testMultiplyFloatAsStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.floatValAsString');
    }

    public function testMultiplyStringStartingByIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.stringValStartingByInteger');
    }

    public function testMultiplyFloatStartingByFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.stringValStartingByFloat');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy('nonexistent', 2);
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonMultiplyNumBy(Keys::DEFAULT_KEY, 2, '.nonexistent');
    }
}
