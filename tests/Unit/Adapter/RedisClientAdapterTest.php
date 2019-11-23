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

namespace Averias\RedisJson\Tests\Unit\Adapter;

use Averias\RedisJson\Enum\JsonCommands;
use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Adapter\RedisClientAdapter;
use Averias\RedisJson\Connection\ConnectionOptions;
use Averias\RedisJson\Exception\ConnectionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;
use Redis;
use Exception;

class RedisClientAdapterTest extends TestCase
{
    /**
     * @dataProvider getConnectionExceptionDataProvider
     * @param string $connectMethodName
     * @param bool $isPersistentConnection
     */
    public function testConnectionExceptions(string $connectMethodName, bool $isPersistentConnection): void
    {
        $this->expectException(ConnectionException::class);

        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            $isPersistentConnection,
            0,
            $this->never(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            $connectMethodName,
            false,
            0,
            $this->never(),
            false,
            $this->never(),
            $this->never()
        );

        new RedisClientAdapter($redisMock, $connectionOptionsMock);
    }

    /**
     * @dataProvider getSuccessConnectionDataProvider
     * @param string $connectMethodName
     * @param bool $isPersistentConnection
     * @param int $databaseIndex
     * @param InvokedCount $selectMethodExpectation
     * @param InvokedCount $getDatabaseMethodExpectation
     */
    public function testSuccessConnection(
        string $connectMethodName,
        bool $isPersistentConnection,
        int $databaseIndex,
        InvokedCount $selectMethodExpectation,
        InvokedCount $getDatabaseMethodExpectation
    ): void {
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            $isPersistentConnection,
            $databaseIndex,
            $getDatabaseMethodExpectation,
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            $connectMethodName,
            true,
            $databaseIndex,
            $selectMethodExpectation,
            false,
            $this->never(),
            $this->once()
        );

        new RedisClientAdapter($redisMock, $connectionOptionsMock);
    }

    public function testExecuteCommandByName(): void
    {
        $methodName = 'hset';
        $arguments = ['hash-test', 'hash-field', 'hash-value'];

        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method($methodName)
            ->with(...$arguments)
            ->willReturn(true);

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeCommandByName($methodName, $arguments);

        $this->assertTrue($result);
    }

    public function testExecuteCommandByNameException(): void
    {
        $this->expectException(ResponseException::class);

        $methodName = 'hset';
        $arguments = ['hash-test', 'hash-field', 'hash-value'];

        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method($methodName)
            ->with(...$arguments)
            ->willThrowException(new Exception());

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $adapter->executeCommandByName($methodName, $arguments);
    }

    public function testExecuteRawCommand(): void
    {
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method('rawCommand')
            ->with(JsonCommands::SET, 'key', '.')
            ->willReturn(true);

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeRawCommand(JsonCommands::SET, 'key', '.');

        $this->assertTrue($result);
    }

    public function testExecuteRawCommandException(): void
    {
        $this->expectException(ResponseException::class);

        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method('rawCommand')
            ->with(JsonCommands::SET, 'key', '.')
            ->willThrowException(new ResponseException());

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $adapter->executeRawCommand(JsonCommands::SET, 'key', '.');
    }

    public function testExecuteJsonCommand(): void
    {
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method('rawCommand')
            ->with(JsonCommands::SET, 'key', '.')
            ->willReturn('OK');

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeJsonCommand(JsonCommands::SET, ['key'], ['key', '.']);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider getExecuteJsonCommandExceptionDataProvider
     * @param $rawCommandReturnValue
     * @param string $exceptionMessage
     * @throws ConnectionException
     */
    public function testExecuteJsonCommandException($rawCommandReturnValue, string $exceptionMessage): void
    {
        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage($exceptionMessage);
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->once(),
            $this->once()
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            true,
            $this->once(),
            $this->once()
        );

        $redisMock->expects($this->once())
            ->method('rawCommand')
            ->with(JsonCommands::SET, 'key', '.')
            ->willReturn($rawCommandReturnValue);

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeJsonCommand(JsonCommands::SET, ['key'], ['key', '.']);

        $this->assertTrue($result);
    }

    public function testReconnectAfterCheckConnection(): void
    {
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->exactly(2),
            $this->exactly(2),
            $this->exactly(2)
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            true,
            0,
            $this->never(),
            false,
            $this->once(),
            $this->exactly(2)
        );

        $redisMock->expects($this->once())
            ->method('rawCommand')
            ->with(JsonCommands::SET, 'key', '.')
            ->willReturn('OK');

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeJsonCommand(JsonCommands::SET, ['key'], ['key', '.']);

        $this->assertTrue($result);
    }

    public function testReconnectException(): void
    {
        $this->expectException(ResponseException::class);
        $connectionOptionsMock = $this->getConnectionOptionsMockForSuccessConnection(
            false,
            0,
            $this->once(),
            $this->exactly(2),
            $this->exactly(2)
        );

        $redisMock = $this->getRedisMockForSuccessConnection(
            'connect',
            null,
            0,
            $this->never(),
            false,
            $this->once(),
            $this->once()
        );
        $redisMock->expects($this->any())
            ->method('connect')
            ->with('localhost')
            ->will($this->onConsecutiveCalls(true, false));

        $adapter = new RedisClientAdapter($redisMock, $connectionOptionsMock);
        $result = $adapter->executeJsonCommand(JsonCommands::SET, ['key'], ['key', '.']);

        $this->assertTrue($result);
    }

    protected function getConnectionOptionsMock(): MockObject
    {
        return $this->getMockBuilder(ConnectionOptions::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConnectionValues', 'getDatabase', 'isPersistent'])
            ->getMock();
    }

    protected function getConnectionOptionsMockForSuccessConnection(
        bool $isPersistentConnection,
        int $databaseIndex,
        InvokedCount $getDatabaseMethodExpectation,
        InvokedCount $getConnectionValuesMethodExpectation,
        InvokedCount $isPersistentMethodExpectation
    ): MockObject {
        $connectionValues = ['localhost'];
        $connectionOptionsMock = $this->getConnectionOptionsMock();
        $connectionOptionsMock->expects($isPersistentMethodExpectation)
            ->method('isPersistent')
            ->willReturn($isPersistentConnection);
        $connectionOptionsMock->expects($getConnectionValuesMethodExpectation)
            ->method('getConnectionValues')
            ->willReturn($connectionValues);
        $connectionOptionsMock->expects($getDatabaseMethodExpectation)
            ->method('getDatabase')
            ->willReturn($databaseIndex);

        return $connectionOptionsMock;
    }

    protected function getRedisMock(): MockObject
    {
        return $this->getMockBuilder(Redis::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getLastError',
                    'rawCommand',
                    'select',
                    'setOption',
                    'pconnect',
                    'connect',
                    'isConnected',
                    'hset'
                ]
            )
            ->getMock();
    }

    protected function getRedisMockForSuccessConnection(
        string $connectMethodName,
        ?bool $returnValueForConnectMethod,
        int $databaseIndex,
        InvokedCount $selectMethodExpectation,
        bool $isConnected,
        ?InvokedCount $isConnectedMethodExpectation,
        InvokedCount $setOptionMethodExpectation
    ): MockObject {
        $connectionValues = ['localhost'];
        $redisMock = $this->getRedisMock();
        if (!is_null($returnValueForConnectMethod)) {
            $redisMock->expects($this->any())
                ->method($connectMethodName)
                ->with(...$connectionValues)
                ->willReturn($returnValueForConnectMethod);
        }
        $redisMock->expects($setOptionMethodExpectation)
            ->method('setOption')
            ->with(Redis::OPT_REPLY_LITERAL, true);
        $redisMock->expects($selectMethodExpectation)
            ->method('select')
            ->with($databaseIndex);
        $redisMock->expects($isConnectedMethodExpectation)
            ->method('isConnected')
            ->willReturn($isConnected);

        return $redisMock;
    }

    public function getSuccessConnectionDataProvider(): array
    {
        return [
            ['pconnect', true, 1, $this->once(), $this->exactly(2)],
            ['connect', false, 1, $this->once(), $this->exactly(2)],
            ['pconnect', true, 0, $this->never(), $this->once()],
            ['connect', false, 0, $this->never(), $this->once()]
        ];
    }

    public function getConnectionExceptionDataProvider(): array
    {
        return [
            ['pconnect', true],
            ['connect', false]
        ];
    }

    public function getExecuteJsonCommandExceptionDataProvider()
    {
        return [
            [18, "expected string response but got 'integer'"],
            ['KO', "expected 'OK' string response but got 'KO'"],
            [false, "something was wrong when executing JSON.SET command, possible reasons: NX or NX conditions were not met, no root path for new keys, ..."]
        ];
    }
}
