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

class JsonArrayPopCommandTest extends BaseTestIntegration
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.colors', ...static::getArrayColors());
    }

    public function testArrayPop()
    {
        $this->assertEquals(['cyan', 'pink'], static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors'));
        $this->assertEquals(4, static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testArrayShift()
    {
        $this->assertEquals('white', static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors', 0));
        $this->assertEquals(3, static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testArrayExtractFromTheMiddle()
    {
        $this->assertEquals('yellow', static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors', 1));
        $this->assertEquals(2, static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testOutOfRange()
    {
        $this->assertEquals('purple', static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors', 10));
        $this->assertEquals(1, static::$reJsonClient->jsonArrayLength(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testPopFromIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.age');
    }

    public function testPopFromFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.height');
    }

    public function testPopFromObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.location');
    }

    public function testPopFromBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.license');
    }

    public function testPopFromStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.name');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop('nonexistent', '.colors');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.nonexistent');
    }

    public function testEmptyArrayPopException()
    {
        $this->expectException(ResponseException::class);
        $this->assertEquals('black', static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors'));
        static::$reJsonClient->jsonArrayPop(Keys::DEFAULT_KEY, '.colors');
    }

    public static function getArrayColors()
    {
        return ['yellow', 'purple', ['cyan', 'pink']];
    }
}
