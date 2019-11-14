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

namespace Averias\RedisJson\Tests\Unit\Parser\Response;

use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Parser\Response\IntegerToBoolean;
use PHPUnit\Framework\TestCase;

class IntegerToBooleanTest extends TestCase
{
    public function testResponseIsNotIntegerException():void
    {
        $this->expectException(ResponseException::class);
        $parser = new IntegerToBoolean();
        $parser->parse('foo');
    }

    /**
     * @dataProvider getDataProvider
     * @param int $response
     * @param bool $expected
     */
    public function testParse(int $response, bool $expected): void
    {
        $parser = new IntegerToBoolean();
        $result = $parser->parse($response);
        $this->assertSame($expected, $result);
    }

    public function getDataProvider(): array
    {
        return [
            [1, true],
            [0, false]
        ];
    }
}
