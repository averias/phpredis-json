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

class JsonArrayIndexCommandTest extends BaseTestIntegration
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$reJsonClient->jsonArrayAppend(Keys::DEFAULT_KEY, '.colors', ...static::getArrayColors());
    }

    /**
     * @dataProvider getStoredColorIndexes
     * @param string $colorValue
     * @param int $colorIndex
     */
    public function testArrayIndex(string $colorValue, int $colorIndex)
    {
        $whiteColorIndex = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, $colorValue, '.colors');
        $this->assertEquals($colorIndex, $whiteColorIndex);
    }

    public function testArrayIndexSlice()
    {
        $secondWhiteColorIndex = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'white', '.colors', 1);
        $this->assertEquals(8, $secondWhiteColorIndex);

        $secondBlackColorIndex = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'black', '.colors', 2);
        $this->assertEquals(7, $secondBlackColorIndex);

        $secondBlackColorIndex = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'black', '.colors', 6, -1);
        $this->assertEquals(7, $secondBlackColorIndex);
    }

    public function testNotFoundValue()
    {
        $nonExistentColor = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'orange', '.colors');
        $this->assertEquals(-1, $nonExistentColor);

        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', 3);
        $this->assertEquals(-1, $colorNotFound);
    }

    public function testOutOfRange()
    {
        // out of ranges in slice are ignored and the original array is considered
        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', 0, -15);
        $this->assertEquals(-1, $colorNotFound);

        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', 12, 15);
        $this->assertEquals(-1, $colorNotFound);
    }

    public function testIndexForIntegerException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, '3', '.age');
    }

    public function testIndexForFloatException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, '1.7', '.height');
    }

    public function testIndexForObjectException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'country', '.location');
    }

    public function testIndexForBooleanException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'true', '.license');
    }

    public function testIndexForStringException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 't', '.name');
    }

    public function testNonExistentKeyException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex('nonexistent', 'green', '.colors');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.nonexistent');
    }

    public function testInverseRange()
    {
        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', 15, -15);
        $this->assertEquals(-1, $colorNotFound);
    }

    public function testNegativeIndexes()
    {
        // The slice of the array where the search is made is just the array with only the first element in the array,
        // since -9 = means the position 0 in the array and -8 means the position 1 in the array, so 'green' color,
        // which is in the position 2 is not found
        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', -9, -8);
        $this->assertEquals(-1, $colorNotFound);

        // in this assertion 'green' color is found since the range contains the first 3 values in the array, since -6
        // means position 3 in the array counting from the end of the array
        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', -9, -6);
        $this->assertEquals(2, $colorNotFound);

        // color is not found since the search is made on an empty array, since -15 value set start and stop to 0
        $colorNotFound = static::$reJsonClient->jsonArrayIndex(Keys::DEFAULT_KEY, 'green', '.colors', -15, -15);
        $this->assertEquals(-1, $colorNotFound);
    }

    public static function getArrayColors()
    {
        return ['green', 'yellow', 'red', 'purple', 'cyan', 'black', 'white'];
    }

    public function getStoredColorIndexes()
    {
        return[
            ['white', 0],
            ['black', 1],
            ['green', 2],
            ['yellow', 3],
            ['red', 4],
            ['purple', 5],
            ['cyan', 6]
        ];
    }
}
