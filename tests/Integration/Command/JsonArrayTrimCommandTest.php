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

class JsonArrayTrimCommandTest extends BaseTestIntegration
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.colors', ...static::getArrayColors());
    }

    public function testArrayTrimAtTheBeginning()
    {
        $this->assertEquals(8, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 7, '.colors'));
        $this->assertNotContains('navy', static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testArrayTrimInTheMiddle()
    {
        $this->assertEquals(6, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 1, 6, '.colors'));

        $colorsArray = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');
        $this->assertNotContains('white', $colorsArray);
        $this->assertNotContains('orange', $colorsArray);
    }

    public function testArrayTrimAtTheEnd()
    {
        $this->assertEquals(5, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 4, '.colors'));

        $this->assertNotContains('cyan', static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testArrayTrimForNegativeIndexes()
    {
        // since the current length of the array is 5, start = -5 means position 0 in the array and stop = 7, means
        // last position in the array, the result array is equal to the original
        $currentArray = static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors');
        $newArraySize = static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, -5, 7, '.colors');
        $this->assertEquals(5, $newArraySize);

        $this->assertEquals($currentArray, static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));

        // similar as above assertion, stop = -1 means last position in the array
        $this->assertEquals(5, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, -5, -1, '.colors'));

        $this->assertEquals($currentArray, static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));

        // stop = -2 means second-to-last position in the array, so the last element in the array is trimmed
        $this->assertEquals(4, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, -5, -2, '.colors'));

        $this->assertNotContains('purple', static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));

        // the array has length = 4, start = -3 is the 2nd position in the array so 1st element is trimmed
        $this->assertEquals(3, static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, -3, -1, '.colors'));

        $this->assertNotContains('black', static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY, '.colors'));
    }

    public function testTrimIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 20, '.age');
    }

    public function testTrimFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 20, '.height');
    }

    public function testTrimObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 20, '.location');
    }

    public function testTrimBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 20, '.license');
    }

    public function testTrimStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 20, '.name');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim('nonexistent', 0, 10, '.colors');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 0, 10, '.nonexistent');
    }

    public function testArrayTrimAnEmptyArray()
    {
        // an empty array is trimmed if start > array length or start > stop
        $newArraySize = static::$reJsonClient->jsonArrayTrim(Keys::DEFAULT_KEY, 4, 1, '.colors');
        $this->assertEquals(0, $newArraySize);
    }

    public static function getArrayColors()
    {
        return ['green', 'yellow', 'red', 'purple', 'cyan', 'orange', 'navy'];
    }
}








