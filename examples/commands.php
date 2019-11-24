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
use Averias\RedisJson\Factory\RedisJsonClientFactory;
use Averias\RedisJson\Tests\Integration\BaseTestIntegration;

/** @var RedisJsonClientFactory $redisJsonClientFactory */
$redisJsonClientFactory = new RedisJsonClientFactory();

/** @var RedisJsonClient $defaultClient */
$client = $redisJsonClientFactory->createClient([
    'database' => 15
]);

// create a constant for the key name
const OBJECT_KEY = 'test-object';
const SECONDARY = 'secondary-test-object';
const COLORS_KEY = '.colors';

/**
 * store default data
 *  [
 *     'name' => 'Peter',
 *     'age' => 38,
 *     'height' => 1.79,
 *     'location' => [
 *          'address' => 'Pub Street, 39',
 *          'city' => 'Limerick',
 *          'country' => 'Ireland'
 *     ],
 *     'colors' => ['white', 'black'],
 *     'license' => true
 *  ];
 */


$client->jsonSet(OBJECT_KEY, BaseTestIntegration::$defaultData);

echo sprintf("*** Added object to '%s' key: %s", OBJECT_KEY, PHP_EOL);
echo json_encode($client->jsonGet(OBJECT_KEY)) . PHP_EOL . PHP_EOL;

// append to array
$result = $client->jsonArrayAppend(OBJECT_KEY, COLORS_KEY, 'blue', 'white');
echo sprintf("*** Appended colors 'blue' and 'white' to '.colors' path %s", PHP_EOL);
echo sprintf("array after appending: %s %s", json_encode($client->jsonGet(OBJECT_KEY, COLORS_KEY), true), PHP_EOL);
echo sprintf("new length of the array: %d %s", $result, PHP_EOL . PHP_EOL);

// insert into array
$result = $client->jsonArrayInsert(OBJECT_KEY, COLORS_KEY, 3, 'green');
echo sprintf("*** Inserted color 'green' into '.colors' path in position (index) 3 %s", PHP_EOL);
echo sprintf("array after inserting: %s %s", json_encode($client->jsonGet(OBJECT_KEY, COLORS_KEY), true), PHP_EOL);
echo sprintf("new length of the array: %d %s", $result, PHP_EOL . PHP_EOL);

// get the index of one element in a array
$result = $client->jsonArrayIndex(OBJECT_KEY, 'white', COLORS_KEY);
echo sprintf("*** Index of color 'white' (duplicated) when searching from the beginning %s", PHP_EOL);
echo sprintf("index of 'white' color : %d %s", $result, PHP_EOL . PHP_EOL);
$result = $client->jsonArrayIndex(OBJECT_KEY, 'white', COLORS_KEY, -3);
echo sprintf("*** Index of color 'white' (duplicated) when searching from the end %s", PHP_EOL);
echo sprintf("index of 'white' color : %d %s", $result, PHP_EOL . PHP_EOL);

// remove element from array
$result = $client->jsonArrayPop(OBJECT_KEY, COLORS_KEY);
echo sprintf("*** Removed last element in '.colors' path %s", PHP_EOL);
echo sprintf("removed element: %s %s", $result, PHP_EOL . PHP_EOL);
$result = $client->jsonArrayPop(OBJECT_KEY, COLORS_KEY, 2);
echo sprintf("*** Removed element in index 2 in '.colors' path %s", PHP_EOL);
echo sprintf("removed element: %s %s", $result, PHP_EOL);

// length of the array
$result = $client->jsonArrayLength(OBJECT_KEY, COLORS_KEY);
echo sprintf("new length of the array after popping it: %s %s", $result, PHP_EOL);
echo sprintf("array after popping: %s %s", json_encode($client->jsonGet(OBJECT_KEY, COLORS_KEY), true), PHP_EOL . PHP_EOL);

// trim array
$result = $client->jsonArrayTrim(OBJECT_KEY, 1, 2, COLORS_KEY);
echo sprintf("*** Trim array in '.colors' path from index 1 to 2 %s", PHP_EOL);
echo sprintf("new length of the array after trimming: %s %s", $result, PHP_EOL);
echo sprintf("array after trimming: %s %s", json_encode($client->jsonGet(OBJECT_KEY, COLORS_KEY), true), PHP_EOL . PHP_EOL);

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

// append string
echo sprintf("*** Appended string ' Newman' to '.name' path %s", PHP_EOL);
echo sprintf("current value of '.name' path: %s %s", $client->jsonGet(OBJECT_KEY, '.name'), PHP_EOL);
$result = $client->jsonStringAppend(OBJECT_KEY, ' Newman', '.name');
echo sprintf(
    "Value of '.name' path after appending string ' Newman': %s %s",
    $client->jsonGet(OBJECT_KEY, '.name'),
    PHP_EOL
);
echo sprintf("new string length of '.name' path: %s %s", $result, PHP_EOL . PHP_EOL);

// string length
echo sprintf("*** String length of '.location.city' path %s", PHP_EOL);
echo sprintf("value in '.location.city' path: %s %s", $client->jsonGet(OBJECT_KEY, '.location.city'), PHP_EOL);
$result = $client->jsonStringLength(OBJECT_KEY, '.location.city');
echo sprintf("length of '.location.city': %s %s", $result, PHP_EOL . PHP_EOL);

// delete
$client->jsonSet(OBJECT_KEY, "I'm afraid I'm gonna be deleted :(", '.toBeDeleted');
echo sprintf("*** Added value to path '.toBeDeleted' to %s key: %s", OBJECT_KEY, PHP_EOL);
echo json_encode($client->jsonGet(OBJECT_KEY), true) . PHP_EOL;
$client->jsonDelete(OBJECT_KEY, '.toBeDeleted');
echo sprintf("*** Deleted value in path '.toBeDeleted': %s", PHP_EOL);
echo json_encode($client->jsonGet(OBJECT_KEY), true) . PHP_EOL;

// get Redis Serialization Protocol (ESP)
echo sprintf("*** RESP of data stored in root path for %s key: %s", OBJECT_KEY, PHP_EOL);
echo json_encode($client->jsonGetAsResp(OBJECT_KEY), true) . PHP_EOL . PHP_EOL;

// Get from 2 paths
echo sprintf("*** Getting data from paths '.colors' and '.location' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo json_encode($client->jsonGet(OBJECT_KEY, COLORS_KEY, '.location'), true) . PHP_EOL . PHP_EOL;

// increment by integer
echo sprintf("*** Incremented by 2 the value stored in path '.age' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo sprintf("previous '.age': %d%s", $client->jsonGet(OBJECT_KEY, '.age'), PHP_EOL);
$result = $client->jsonIncrementNumBy(OBJECT_KEY, 2, '.age');
echo sprintf("updated '.age': %d%s", $result, PHP_EOL . PHP_EOL);

// increment by float
echo sprintf("*** Incremented by 0.03 the value stored in path '.height' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo sprintf("previous '.height': %.2f%s", $client->jsonGet(OBJECT_KEY, '.height'), PHP_EOL);
$result = $client->jsonIncrementNumBy(OBJECT_KEY, 0.03, '.height');
echo sprintf("updated '.height': %.2f%s", $result, PHP_EOL . PHP_EOL);

// multiply by integer
echo sprintf("*** Multiplied by 2 the value stored in path '.age' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo sprintf("previous '.age': %d%s", $client->jsonGet(OBJECT_KEY, '.age'), PHP_EOL);
$result = $client->jsonMultiplyNumBy(OBJECT_KEY, 2, '.age');
echo sprintf("updated '.age': %d%s", $result, PHP_EOL . PHP_EOL);

// multiply by float
echo sprintf("*** Multiplied by 1.03 the value stored in path '.height' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo sprintf("previous '.height': %.4f%s", $client->jsonGet(OBJECT_KEY, '.height'), PHP_EOL);
$result = $client->jsonMultiplyNumBy(OBJECT_KEY, 1.03, '.height');
echo sprintf("updated '.height': %.4f%s", $result, PHP_EOL . PHP_EOL);

// memory usage
echo sprintf("*** Memory used by data stored in path '.' for %s key: %s", OBJECT_KEY, PHP_EOL);
echo sprintf("memory (bytes): %d%s", $client->jsonMemoryUsage(OBJECT_KEY), PHP_EOL);
$result = json_encode($client->jsonGet(OBJECT_KEY));
echo sprintf("data (array shown as json): %s%s", $result, PHP_EOL . PHP_EOL);

// multi get
echo sprintf("*** Setting new key '%s' with original default data %s", SECONDARY, PHP_EOL);
$client->jsonSet(SECONDARY, BaseTestIntegration::$defaultData);
echo sprintf(
    "*** Getting '.color' path value for 3 keys: '%s', '%s' and '%s' (non-existent key) %s",
    SECONDARY,
    OBJECT_KEY,
    'nonexistent',
    PHP_EOL
);
$result = $client->jsonMultiGet([OBJECT_KEY, SECONDARY, 'nonexistent'], COLORS_KEY);
echo sprintf("'.colors' in '%s' key: %s%s", OBJECT_KEY, json_encode($result[0]), PHP_EOL);
echo sprintf("'.colors' in '%s' key: %s%s", SECONDARY, json_encode($result[1]), PHP_EOL);
echo sprintf("'.colors' in '%s' key: %s%s", 'nonexistent', json_encode($result[2]), PHP_EOL . PHP_EOL);

// type
echo sprintf("*** Data type for the different paths in key '%s' %s", OBJECT_KEY, PHP_EOL);
echo sprintf("type for '.name' path: %s%s", $client->jsonType(OBJECT_KEY, '.name'), PHP_EOL);
echo sprintf("type for '.age' path: %s%s", $client->jsonType(OBJECT_KEY, '.age'), PHP_EOL);
echo sprintf("type for '.height' path: %s%s", $client->jsonType(OBJECT_KEY, '.height'), PHP_EOL);
echo sprintf("type for '.location' path: %s%s", $client->jsonType(OBJECT_KEY, '.location'), PHP_EOL);
echo sprintf("type for '.colors' path: %s%s", $client->jsonType(OBJECT_KEY, COLORS_KEY), PHP_EOL);
echo sprintf("type for '.license' path: %s%s", $client->jsonType(OBJECT_KEY, '.license'), PHP_EOL . PHP_EOL);
