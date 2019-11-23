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

use Averias\RedisJson\Adapter\AdapterProvider;
use Averias\RedisJson\Adapter\RedisClientAdapterInterface;
use Averias\RedisJson\Enum\Module;
use Averias\RedisJson\Enum\Version;
use Averias\RedisJson\Exception\InvalidRedisVersionException;
use Averias\RedisJson\Exception\RedisJsonModuleNotInstalledException;
use Averias\RedisJson\Adapter\RedisClientAdapter;
use Averias\RedisJson\Validator\RedisClientValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AdapterProviderTest extends TestCase
{
    /**
     * @dataProvider getDataProviderForRedisClientConfigurationExceptions
     * @param array $info
     * @param array $moduleList
     * @param string $exception
     * @param string $exceptionMessage
     */
    public function testRedisClientConfigurationExceptions(
        array $info,
        array $moduleList,
        string $exception,
        string $exceptionMessage
    ) {
        $this->expectException($exception);
        $this->expectExceptionMessage($exceptionMessage);

        $providerMock = $this->getMockBuilder(AdapterProvider::class)
            ->setConstructorArgs([new RedisClientValidator()])
            ->onlyMethods(['getRedisClient'])
            ->getMock();
        $providerMock->method('getRedisClient')
            ->willReturn($this->getRedisClientMock($info, $moduleList));
        $providerMock->get();
    }

    public function testGetRedisClientAdapterInterface()
    {
        $providerMock = $this->getMockBuilder(AdapterProvider::class)
            ->setConstructorArgs([new RedisClientValidator()])
            ->onlyMethods(['getRedisClient'])
            ->getMock();
        $providerMock->method('getRedisClient')
            ->willReturn(
                $this->getRedisClientMock(
                    ['redis_version' => Version::REDIS_JSON_CLIENT_4X0],
                    [[Module::REDIS_JSON_MODULE_NAME]]
                )
            );
        $adapter = $providerMock->get();
        $this->assertInstanceOf(RedisClientAdapterInterface::class, $adapter);
    }

    protected function getRedisClientMock(array $info, array $moduleList): MockObject
    {
        $redisClientMock =  $this->getMockBuilder(RedisClientAdapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['executeCommandByName', 'executeRawCommand'])
            ->getMock();

        $redisClientMock->method('executeCommandByName')
            ->with('INFO')
            ->willReturn($info);
        $redisClientMock->method('executeRawCommand')
            ->with('MODULE', 'list')
            ->willReturn($moduleList);

        return $redisClientMock;
    }

    public function getDataProviderForRedisClientConfigurationExceptions(): array
    {
        return [
            [
                ['redis_version' => '3.2'],
                [['foo', 'bar'], ['zoo', 'bad']],
                InvalidRedisVersionException::class,
                'invalid redis server version 3.2, expected 4.0+.'
            ],
            [
                ['redis_version' => '4.3'],
                [['foo', 'bar'], ['zoo', 'bad']],
                RedisJsonModuleNotInstalledException::class,
                'RedisJson module not installed in Redis server.'
            ]
        ];
    }
}
