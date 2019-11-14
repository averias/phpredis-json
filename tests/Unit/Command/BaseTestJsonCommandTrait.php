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

namespace Averias\RedisJson\Tests\Unit\Command;

use Averias\RedisJson\Client\RedisJsonClient;
use Averias\RedisJson\Client\RedisJsonClientInterface;
use Averias\RedisJson\Adapter\RedisClientAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BaseTestJsonCommandTrait extends TestCase
{
    public function getRedisJsonClient($returnValue, array $arguments): RedisJsonClientInterface
    {
        return new RedisJsonClient($this->getRedisClientAdapterMock($returnValue, $arguments));
    }

    public function getRedisClientAdapterMock($returnValue, array $arguments): MockObject
    {
        $mock = $this->getMockBuilder(RedisClientAdapter::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->any())
            ->method('executeJsonCommand')
            ->with(...$arguments)
            ->willReturn($returnValue);

        return $mock;
    }
}
