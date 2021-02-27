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

class JsonGetAsRespCommandTest extends BaseTestIntegration
{
    public function testJsonObjectIsRepresentedAsRESPArrays()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY);
        $this->assertEquals('{', $response[0]);

        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.location');
        $this->assertEquals('{', $response[0]);
    }

    public function testStringPathIsRepresentedAsRESPString()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.name');
        $this->assertEquals('Peter', $response);
    }

    public function testIntegerPathIsRepresentedAsRESPInteger()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.age');
        $this->assertEquals(38, $response);
    }

    public function testFloatPathIsRepresentedAsRESPString()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.height');
        $this->assertEquals('1.79', $response);
    }

    public function testArrayPathIsRepresentedAsRESPArray()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.colors');
        $this->assertEquals('[', $response[0]);
        $this->assertEquals('white', $response[1]);
        $this->assertEquals('black', $response[2]);
    }

    public function testBooleanPathIsRepresentedAsRESPString()
    {
        $response = static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.license');
        $this->assertEquals('true', $response);
    }

    public function testNonExistentKeyReturnsException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonGetAsResp('nonexistent');
    }

    public function testNonExistentPathException()
    {
        $this->expectException(ResponseException::class);
        static::$reJsonClient->jsonGetAsResp(Keys::DEFAULT_KEY, '.nonexistent');
    }
}
