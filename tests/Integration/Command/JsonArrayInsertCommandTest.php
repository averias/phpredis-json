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

class JsonArrayInsertCommandTest extends BaseTestIntegration
{
    public function testInsertAtTheBeginning()
    {
        $arraySize = static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.colors', 0, 'green', 'yellow');
        $this->assertEquals(4, $arraySize);
        $this->assertEquals(0, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors'));
        $this->assertEquals(1, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'yellow', '.colors'));
        $this->assertEquals(2, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'white', '.colors'));
        $this->assertEquals(3, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'black', '.colors'));
    }

    public function testInsertInTheMiddle()
    {
        $arraySize = static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.colors', 2, 'red', 'blue');
        $this->assertEquals(6, $arraySize);
        $this->assertEquals(0, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors'));
        $this->assertEquals(1, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'yellow', '.colors'));
        $this->assertEquals(2, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'red', '.colors'));
        $this->assertEquals(3, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'blue', '.colors'));
        $this->assertEquals(4, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'white', '.colors'));
        $this->assertEquals(5, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'black', '.colors'));
    }

    public function testInsertAtTheEnd()
    {
        $arraySize = static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.colors', -2, 'cyan', 'orange');
        $this->assertEquals(8, $arraySize);
        $this->assertEquals(0, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors'));
        $this->assertEquals(1, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'yellow', '.colors'));
        $this->assertEquals(2, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'red', '.colors'));
        $this->assertEquals(3, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'blue', '.colors'));
        $this->assertEquals(4, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'cyan', '.colors'));
        $this->assertEquals(5, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'orange', '.colors'));
        $this->assertEquals(6, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'white', '.colors'));
        $this->assertEquals(7, static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'black', '.colors'));
    }

    public function testInsertInIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.age', 0, 'orange');
    }

    public function testInsertInFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.height', 0, 'orange');
    }

    public function testInsertInObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.location', 0, 'orange');
    }

    public function testInsertInBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.license', 0, 'orange');
    }

    public function testInsertInStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.name', 0, 'orange');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert('nonexistent', '.colors', 0, 'purple');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayInsert(Keys::DEFAULT_KEY, '.nonexistent', 0, 'purple');
    }
}
