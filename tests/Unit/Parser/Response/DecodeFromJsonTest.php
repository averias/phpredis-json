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

use Averias\RedisJson\Parser\Response\DecodeFromJson;
use PHPUnit\Framework\TestCase;

class DecodeFromJsonTest extends TestCase
{
    public function testNoDecodeIfFalse()
    {
        $parser = new DecodeFromJson();
        $result = $parser->parse(false);
        $this->assertNull($result);
    }

    /**
     * @dataProvider getDecodeJsonDataProvider
     * @param string $response
     */
    public function testDecode(string $response)
    {
        $parser = new DecodeFromJson();
        $result = $parser->parse($response);
        $this->assertSame(json_decode($response, true), $result);
    }

    public function getDecodeJsonDataProvider()
    {
        return [
            ['{"key1":"value1","key2":false,"key3":18,"key4":11.2,"key5":["array-value1","array-value2"]}'],
            ['1.79'],
            ['34'],
            ['"Peter"'],
            ["false"]
        ];
    }
}
