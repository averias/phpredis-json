<?php
/**
 * @project   phpredis-json
 * @author    Rafael Campoy <rafa.campoy@gmail.com>
 * @copyright 2019 Rafael Campoy <rafa.campoy@gmail.com>
 * @license   MIT
 * @link      https://github.com/averias/php-rejson
 *
 * Copyright and license information, is included in
 * the LICENSE file that is distributed with this source code.
 */

namespace Examples;

require(dirname(__DIR__).'/vendor/autoload.php');

use Averias\RedisJson\Client\RedisJsonClient;
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use Averias\RedisJson\Tests\Integration\BaseTestIntegration;

/** @var RedisJsonClientFactory $redisJsonClientFactory */
$redisJsonClientFactory = new RedisJsonClientFactory();

/** @var RedisJsonClient $defaultClient */
$client = $redisJsonClientFactory->createClient();

// create a constant for the key name
const OBJECT_KEY = 'test-object';
//define('OBJECT_KEY', 'test-object');

// store default data default data
//    [
//        'name' => 'Peter',
//        'age' => 38,
//        'height' => 1.79,
//        'location' => [
//            'address' => 'Pub Street, 39',
//            'city' => 'Limerick',
//            'country' => 'Ireland'
//        ],
//        'colors' => ['white', 'black'],
//        'license' => true
//    ];
$client->jsonSet(OBJECT_KEY, BaseTestIntegration::$defaultData);

echo sprintf("*** Added object to '%s' key: %s", OBJECT_KEY, PHP_EOL);
echo json_encode($client->jsonGet(OBJECT_KEY), true) . PHP_EOL . PHP_EOL;

// append to array
$result = $client->jsonArrayAppend(OBJECT_KEY, '.colors', 'blue', 'white');
echo sprintf("*** Appended colors 'blue' and 'white' to '.colors' path %s", PHP_EOL);
echo sprintf("array after appending: %s %s", json_encode($client->jsonGet(OBJECT_KEY, '.colors'), true), PHP_EOL);
echo sprintf("new length of the array: %d %s", $result, PHP_EOL . PHP_EOL);

// insert into array
$result = $client->jsonArrayInsert(OBJECT_KEY, '.colors', 3, 'green');
echo sprintf("*** Inserted color 'green' into '.colors' path in position (index) 3 %s", PHP_EOL);
echo sprintf("array after inserting: %s %s", json_encode($client->jsonGet(OBJECT_KEY, '.colors'), true), PHP_EOL);
echo sprintf("new length of the array: %d %s", $result, PHP_EOL . PHP_EOL);

// get the index of one element in a array
$result = $client->jsonArrayIndex(OBJECT_KEY, 'white', '.colors');
echo sprintf("*** Index of color 'white' (duplicated) when searching from the beginning %s", PHP_EOL);
echo sprintf("index of 'white' color : %d %s", $result, PHP_EOL . PHP_EOL);
$result = $client->jsonArrayIndex(OBJECT_KEY, 'white', '.colors', -3);
echo sprintf("*** Index of color 'white' (duplicated) when searching from the end %s", PHP_EOL);
echo sprintf("index of 'white' color : %d %s", $result, PHP_EOL . PHP_EOL);

// remove element from array
$result = $client->jsonArrayPop(OBJECT_KEY, '.colors');
echo sprintf("*** Removed last element in '.colors' path %s", PHP_EOL);
echo sprintf("removed element: %s %s", $result, PHP_EOL . PHP_EOL);
$result = $client->jsonArrayPop(OBJECT_KEY, '.colors', 2);
echo sprintf("*** Removed element in index 2 in '.colors' path %s", PHP_EOL);
echo sprintf("removed element: %s %s", $result, PHP_EOL);

// length of the array
$result = $client->jsonArrayLength(OBJECT_KEY, '.colors');
echo sprintf("new length of the array after popping it: %s %s", $result, PHP_EOL);
echo sprintf("array after popping: %s %s", json_encode($client->jsonGet(OBJECT_KEY, '.colors'), true), PHP_EOL . PHP_EOL);

// trim array
$result = $client->jsonArrayTrim(OBJECT_KEY, 1, 2, '.colors');
echo sprintf("*** Trim array in '.colors' path from index 1 to 2 %s", PHP_EOL);
echo sprintf("new length of the array after trimming: %s %s", $result, PHP_EOL);
echo sprintf("array after trimming: %s %s", json_encode($client->jsonGet(OBJECT_KEY, '.colors'), true), PHP_EOL . PHP_EOL);

// get keys of a object as array
$result = $client->jsonObjectKeys(OBJECT_KEY); // by default path = '.' (root path)
echo sprintf("*** Get array of keys for the whole object  ('.' path) %s", PHP_EOL);
echo sprintf("array of keys for the root object: %s %s", json_encode($result, true), PHP_EOL . PHP_EOL);
$result = $client->jsonObjectKeys(OBJECT_KEY, '.location');
echo sprintf("*** Get array of keys for '.location' object %s", PHP_EOL);
echo sprintf("array of keys for the object in '.location' path: %s %s", json_encode($result, true), PHP_EOL . PHP_EOL);

// object length
$result = $client->jsonObjectLength(OBJECT_KEY); // by default path = '.' (root path)
echo sprintf("*** Number keys for the whole object  ('.' path) %s", PHP_EOL);
echo sprintf("key length for the root object: %s %s", $result, PHP_EOL . PHP_EOL);
$result = $client->jsonObjectLength(OBJECT_KEY, '.location');
echo sprintf("*** Number keys for '.location' object %s", PHP_EOL);
echo sprintf("key length  for the object in '.location' path: %s %s", $result, PHP_EOL . PHP_EOL);
