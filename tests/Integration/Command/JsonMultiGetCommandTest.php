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

use Averias\RedisJson\Enum\Keys;
use Averias\RedisJson\Tests\Integration\BaseTestIntegration;

class JsonMultiGetCommandTest extends BaseTestIntegration
{
    protected static $data;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$data = static::$defaultData;
        static::$data['email'] = 'some@example.com';

        static::storeData(Keys::EXTENDED_KEY, static::$data);
    }

    public function testPartialMultiGet()
    {
        $result = static::$reJsonClient->jsonMultiGet([Keys::DEFAULT_KEY, Keys::EXTENDED_KEY], '.location');
        $this->assertSame($result[0], $result[1]);
    }

    public function testFullMultiGet()
    {
        $result = static::$reJsonClient->jsonMultiGet([Keys::DEFAULT_KEY, Keys::EXTENDED_KEY]);
        $defaultResult = $result[0];
        $extendedResult = $result[1];

        $this->assertNotEquals($extendedResult, $defaultResult);
        $this->assertArrayNotHasKey('email', $defaultResult);
        $this->assertArrayHasKey('email', $extendedResult);

        unset($extendedResult['email']);

        $this->assertEquals($extendedResult, $defaultResult);
    }

    public function testMultiGetWithOnlyOneKey()
    {
        $result = static::$reJsonClient->jsonMultiGet([Keys::DEFAULT_KEY]);
        $this->assertSame($result[0], static::$defaultData);
    }

    public function testMultiGetWithNoKeys()
    {
        $result = static::$reJsonClient->jsonMultiGet([], '.location');
        $this->assertEmpty($result);

        $result = static::$reJsonClient->jsonMultiGet([]);
        $this->assertEmpty($result);
    }

    public function testNonExistentKeys()
    {
        $result = static::$reJsonClient->jsonMultiGet([Keys::DEFAULT_KEY, 'nonexistent']);

        $this->assertSame(static::$defaultData, $result[0]);
        $this->assertNull($result[1]);
    }

    public function testNonExistentPath()
    {
        $result = static::$reJsonClient->jsonMultiGet([Keys::DEFAULT_KEY, Keys::EXTENDED_KEY], '.nonexistent');

        $this->assertNull($result[0]);
        $this->assertNull($result[1]);
    }
}
