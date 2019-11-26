<?php
/**
 * @project   phpredis-json
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/phpredis-json
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Examples;

require(dirname(__DIR__) . '/vendor/autoload.php');

use Averias\RedisJson\Client\RedisJsonClient;
use Averias\RedisJson\Enum\JsonCommands;
use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;

// instantiate factory
$redisJsonClientFactory = new RedisJsonClientFactory();

/**
 * creates a client with default connection params:
 * [
 *     'host' => '127.0.0.1',
 *     'port' => 6379,
 *     'timeout' => 0.0, // seconds
 *     'retryInterval' => 15 // milliseconds
 *     'readTimeout' => 2, // seconds
 *     'persistenceId' => null // string for persistent connections, null for no persistent ones
 *     'database' => 0 // Redis database index [0..15]
 * ]
 */

/** @var RedisJsonClient $client */
$client = $redisJsonClientFactory->createClient();

// creates a configured client
$client = $redisJsonClientFactory->createClient([
    'host' => '127.0.0.1',
    'port' => 6379,
    'timeout' => 2,
    'database' => 15
]);

/**
 * creates a RedisJsonClient with default connection params:
 * [
 *     'host' => '127.0.0.1',
 *     'port' => 6379,
 *     'timeout' => 0.0, // seconds
 *     'retryInterval' => 15 // milliseconds
 *     'readTimeout' => 2, // seconds
 *     'persistenceId' => null // string for persistent connections, null for no persistent ones
 *     'database' => 0 // Redis database index [0..15]
 * ]
 */

// json commands
$result = $client->jsonSet('people', ["name" => "gafael", "age" => 12]);
echo ($result === true ? 'true' : 'false') . PHP_EOL; // true

$result = $client->jsonGet('people'); // $result = ["name":"gafael","age":12]
echo json_encode($result) . PHP_EOL; // {"name":"gafael","age":12}

$result = $client->jsonGet('people', '.name');
echo $result . PHP_EOL; // "gafael"

$result = $client->jsonGet('people', '.age');
echo $result . PHP_EOL; // 12

// "nonexistent" key does not exists, so a ResponseException is thrown
try {
    $result = $client->jsonGet('nonexistent');
    echo $result . PHP_EOL;
} catch (ResponseException $e) {
    echo "key nonexistent does not exist" . PHP_EOL;
}

// you can also send RedisJSON command as raw commands using "executeRawCommand", you will send a receive JSON values
$result = $client->executeRawCommand(JsonCommands::SET, 'people', '.colors', '["blue", "green"]');
echo $result . PHP_EOL; // 'OK'

$result = $client->executeRawCommand(JsonCommands::GET, 'people', '.');
echo $result . PHP_EOL; // {"name":"gafael","age":12,"colors":["blue","green"]}


// you can also issue redis commands and use RedisJsonClient as "phpredis" client:
echo $client->hset('hash-key', 'age', 34) . PHP_EOL; // 0
echo $client->hget('hash-key', 'age') . PHP_EOL; // 34

// $ret = [true,"val1",true,"val2"]
$ret = $client->multi()
    ->set('key1', 'val1')
    ->get('key1')
    ->set('key2', 'val2')
    ->get('key2')
    ->exec();

echo json_encode($ret) . PHP_EOL;
