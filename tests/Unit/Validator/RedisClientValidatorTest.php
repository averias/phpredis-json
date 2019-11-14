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

namespace Averias\RedisJson\Tests\Unit\Validator;

use Averias\RedisJson\Enum\Module;
use Averias\RedisJson\Validator\RedisClientValidator;
use PHPUnit\Framework\TestCase;

class RedisClientValidatorTest extends TestCase
{
    /**
     * @dataProvider getValidRedisVersionDataProvider
     * @param string $version
     */
    public function testIsValidRedisVersion(string $version)
    {
        $validator = new RedisClientValidator();
        $result = $validator->isValidRedisVersion($version);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider getInvalidRedisVersionDataProvider
     * @param string $version
     */
    public function testIsNotValidRedisVersion(string $version)
    {
        $validator = new RedisClientValidator();
        $result = $validator->isValidRedisVersion($version);
        $this->assertFalse($result);
    }

    public function testIsRedisJsonModuleInstalled()
    {
        $validator = new RedisClientValidator();
        $result = $validator->isRedisJsonModuleInstalled([['foo', 'bar'], [Module::REDIS_JSON_MODULE_NAME]]);
        $this->assertTrue($result);
    }

    public function testIsNotRedisJsonModuleInstalled()
    {
        $validator = new RedisClientValidator();
        $result = $validator->isRedisJsonModuleInstalled([['foo', 'bar'], ['ReJSONNotInstalled']]);
        $this->assertFalse($result);
    }

    public function getValidRedisVersionDataProvider()
    {
        return [
            ['4.0'],
            ['5.0'],
            ['4.0.14'],
            ['5.0.6'],
            ['4'],
            ['5']
        ];
    }

    public function getInvalidRedisVersionDataProvider()
    {
        return [
            ['3.2'],
            ['2.8']
        ];
    }
}
