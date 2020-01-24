[![Test Coverage](https://api.codeclimate.com/v1/badges/0139a26763ee4a0c3343/test_coverage)](https://codeclimate.com/github/averias/phpredis-json/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/0139a26763ee4a0c3343/maintainability)](https://codeclimate.com/github/averias/phpredis-json/maintainability)
[![Build Status](https://travis-ci.org/averias/phpredis-json.svg?branch=master)](https://travis-ci.org/averias/phpredis-json)
[![Packagist Version](https://img.shields.io/packagist/v/averias/phpredis-json.svg)](https://packagist.org/packages/averias/phpredis-json)
[![GitHub](https://img.shields.io/github/license/averias/phpredis-json.svg)](https://github.com/averias/phpredis-json)

# Phpredis-JSON
RedisJson with the PHP Redis extension [phpredis](https://github.com/phpredis/phpredis).

## Intro
Phpredis-JSON provides a full set of commands for [RedisJson Module](https://oss.redislabs.com/redisjson/). 
It's built on top of the `phpredis` and use it as Redis client, 
so you can also take advantage of some of the features included in `phpredis` as Redis client.

## Why?
Although you can issue RedisJSON commands by using some PHP Redis clients which provides you the ability to send 
raw Redis commands, Phpredis-JSON:
- avoids you the task of encoding your PHP data structures to JSON before sending them to Redis 
and decode the JSON responses back from Redis
- it validates JSON encode/decode and throw a proper exception in case of failure
- provides a set of commands as methods of the RedisJSON client  

## Requirements
- Redis server 4.0+ version (RedisJson Module is only available from Redis 4.0+)
- RedisJson Module installed on Redis server as specified in [Building and loading RedisJSON Module](https://oss.redislabs.com/redisjson/#building-and-loading-the-module)
- PHP 7.2+ with PHP Redis extension 5 installed

## Usage
```
<?php

use Averias\RedisJson\Enum\JsonCommands;
use Averias\RedisJson\Exception\ResponseException;
use Averias\RedisJson\Factory\RedisJsonClientFactory;

// *** get a RedisJsonClient ***
$redisJsonClientFactory = new RedisJsonClientFactory();
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
$defaultClient = $redisJsonClientFactory->createClient();

// creates a configured RedisJsonClient
$client = $redisJsonClientFactory->createClient([
    'host' => '127.0.0.1',
    'port' => 6390,
    'timeout' => 2,
    'database' => 15
]);

// *** Redis JSON commands ***
$result = $client->jsonSet('people', ["name" => "gafael", "age" => 12]);
echo ($result === true ? 'true' : 'false') . PHP_EOL; // true

$result = $client->jsonGet('people'); // $result = ["name":"gafael","age":12]
echo json_encode($result) . PHP_EOL; // {"name":"gafael","age":12}

$result =  $client->jsonGet('people', '.name');
echo $result . PHP_EOL; // "gafael"

$result =  $client->jsonGet('people', '.age');
echo $result . PHP_EOL; // 12

// "nonexistent" key does not exist, so a ResponseException is thrown
try {
    $result = $client->jsonGet('nonexistent');
    echo $result . PHP_EOL;
} catch (ResponseException $e) {
    echo "key nonexistent does not exist" . PHP_EOL;
}

// *** you can also send RedisJSON command as raw commands using "RedisJsonClient::executeRawCommand"  ***
// you will send
$result =  $client->executeRawCommand(JsonCommands::SET, 'people', '.colors', '["blue", "green"]');
echo $result . PHP_EOL; // 'OK'

// and receive JSON values
$result =  $client->executeRawCommand(JsonCommands::GET, 'people', '.');
echo $result . PHP_EOL; // {"name":"gafael","age":12,"colors":["blue","green"]}


// *** you can also issue redis commands and use RedisJsonClient as "phpredis" client:
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

```

## Commands
- **RedisJSON commands:** please take a look to the list of [phpredis-json commands](https://github.com/averias/phpredis-json/blob/master/docs/JSON-COMMANDS.md)
- **Phpredis commands:** you can send Redis commands as specified in [phpredis documentation](https://github.com/phpredis/phpredis#table-of-contents)
- **Raw commands:** you can send whatever you want to Redis by using `RedisJsonClient::executeRawCommand`:
```
// raw Redis JSON command
$client->executeRawCommand(JsonCommands::GET, 'people', '.');

// raw Redis command
$client->executeRawCommand('hget, 'hash-key', 'foo');
``` 

## Tests
#### On a local Redis server 4.0+ with RedisJSON module and Redis extension 5 installed
From console run the following command from the root directory of this project:

`./vendor/bin/phpunit`

if you don't have configured your local Redis server in 127.0.0.1:6379 you can set REDIS_TEST_SERVER and REDIS_TEST_PORT 
and REDIS_TEST_DATABASE in `./phpunit.xml` file with your local Redis host, port and database before running the above 
command.
  
#### Docker
Having Docker installed, run the following command in the root directory of this project:

`bash run-tests-docker.sh`

by running the above bash script, two docker services will be built, one with PHP 7.2 with xdebug and redis extensions
enabled and another with the image of `redislab\rejson:1.0.4` (Redis server 5 with RedisJson module installed). 
Then the tests will run inside `phpredis-json` docker service container and finally both container will be stopped.

## Examples
- [Usage](https://github.com/averias/phpredis-json/blob/master/examples/usage.php)
- [Commands](https://github.com/averias/phpredis-json/blob/master/examples/commands.php)

## License
Phpredis-Json code is distributed under MIT license, see [LICENSE](https://github.com/averias/phpredis-json/blob/master/LICENSE) 
file
