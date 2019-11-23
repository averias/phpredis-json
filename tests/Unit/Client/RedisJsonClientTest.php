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

namespace Averias\RedisJson\Tests\Unit\Client;

use Averias\RedisJson\Adapter\RedisClientAdapterInterface;
use Averias\RedisJson\Client\RedisJsonClient;
use Averias\RedisJson\Enum\JsonCommands;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class RedisJsonClientTest extends TestCase
{
    public function testExecuteRawCommand(): void
    {
        $mock = $this->getAdapterMock($this->once(), $this->never());
        $client = new RedisJsonClient($mock);
        $result = $client->executeRawCommand(JsonCommands::DELETE, 'key', 'path');
        $this->assertTrue($result);
    }

    public function testExecuteCommandByName(): void
    {
        $mock = $this->getAdapterMock($this->never(), $this->once());
        $client = new RedisJsonClient($mock);
        $result = $client->hget('hash-test', 'hash-field');
        $this->assertTrue($result);
    }

    protected function getAdapterMock(InvokedCount $rawCommandExpects, InvokedCount $commandByNameExpects): MockObject
    {
        $mock = $this->getMockBuilder(RedisClientAdapterInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['executeJsonCommand', 'executeCommandByName', 'executeRawCommand'])
            ->getMock();
        $mock->expects($rawCommandExpects)
            ->method('executeRawCommand')
            ->with(JsonCommands::DELETE, 'key', 'path')
            ->willReturn(true);
        $mock->expects($commandByNameExpects)
            ->method('executeCommandByName')
            ->with('hget', ['hash-test', 'hash-field'])
            ->willReturn(true);

        return $mock;
    }
}
