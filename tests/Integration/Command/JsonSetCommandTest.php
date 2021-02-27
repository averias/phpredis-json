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

class JsonSetCommandTest extends BaseTestIntegration
{
    public static function setUpBeforeClass(): void
    {
        static::$reJsonClient  = self::getReJsonClient();
    }

    public function testInsertionsWithPHPStructures()
    {
        $this->assertTrue(
            static::$reJsonClient->jsonSet(
                Keys::DEFAULT_KEY,
                ['name' => "Peter", 'age' => 34, 'height' => 1.79]
            )
        );
        $this->assertTrue(
            static::$reJsonClient->jsonSet(
                Keys::DEFAULT_KEY,
                ['address' => 'Pub Street, 39', 'city' =>  'Dublin', 'country' => 'Ireland'],
                '.location'
            )
        );
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, ['white', 'black'], '.colors'));
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, true, '.license'));
    }

    public function testUpdates()
    {
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, '37', '.age'));
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, 38, '.age'));
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, '1.73', '.height'));
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, 1.79, '.height'));
        $this->assertTrue(static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, 'Limerick', '.location.city'));
    }

    public function testUpdateExistingPathWithNXException()
    {
        // with NX option set we cannot update an existing path ('age' already exists)
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, '98', '.age', 'NX');
    }

    public function testUpdateNonExistingPathWithXXException()
    {
        // with XX option set we only can update an existing path ('state' does not exist)
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonSet(Keys::DEFAULT_KEY, 'Leinster', '.location.state', 'XX');
    }

    public function testFinalStoreData()
    {
        $this->assertSame(static::$defaultData, static::$reJsonClient->jsonGet(Keys::DEFAULT_KEY));
    }

    public function testSettingNewKeyInNoRootPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonSet('where-is-my-key', ['name' => "Eva", 'age' => 43], '.somefield');
    }
}
