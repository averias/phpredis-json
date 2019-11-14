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
use Averias\RedisJson\Parser\Response\DecodeArrayOfJson;
use PHPUnit\Framework\TestCase;

class DecodeArrayOfJsonTest extends TestCase
{
    public function testResponseIsNotArrayException():void
    {
        $this->expectException(ResponseException::class);
        $parser = new DecodeArrayOfJson();
        $parser->parse('foo');
    }


    /**
     * @dataProvider getDecodeJsonDataProvider
     * @param array $response
     */
    public function testDecodeArray(array $response)
    {
        $parser = new DecodeArrayOfJson();
        $result = $parser->parse($response);
        $decoded = array_map(
            function ($item) {
                return json_decode($item, true);
            },
            $response
        );
        $this->assertSame($decoded, $result);
    }

    public function getDecodeJsonDataProvider()
    {
        return [
            [['{"key1":"value1","key2":false,"key3":18,"key5":["array-value1","array-value2"]}', '18', 'true']],
            [['1.79', '"Sara"']],
            [['true', 'false']]
        ];
    }
}
