# Phpredis-JSON Commands
Take a look at [command.php](https://github.com/averias/phpredis-json/blob/master/examples/commands.php) where you could
find examples of the different commands. If you want to execute it you will need a Redis server 4.0+ in 127.0.0.1:6379
with [RedisJSON module](https://oss.redislabs.com/redisjson/#building-and-loading-the-module) installed.

You can also take a look at the tests, especially Integration tests. to get a better knowledge of how the different 
commands work.
 
## `Array Commands`

### `Array Append`
It appends one or more items to the array specified in `path`.

`jsonArrayAppend(string $key, string $path, ...$values);`

**Returns:** The length of the new array (int).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, no `values` are provided or we try to append 
item(s) in a no array.

### `Array Index`
It returns the index of one `value` in the array specified in `path`. 
By providing `start` and `stop` params we get a slice from the original array:
- if `start` or `stop` params are negative integers they will be counted from the end of the array so if its absolute 
value for both is greater than the length of the array, that means: (abs(start|stop) > len(array), they will be set to 0
- if `stop` is positive and greater than the length of the array, it will be set to the value of the length of the 
array, stop = len(array)
- if `start` is positive and greater than the length of the array, the result will be an empty array
- if `start` > `stop` the result will be an empty array

`jsonArrayIndex(string $key, string $value, string $path = '.', int $start = 0, int $stop = 0);`

**Returns:** The index value or -1 if the index was not found or was out of range (int).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try this command on no arrays. 

### `Array Insert`
It inserts one or more items to the array specified in `path` in the `index` position.

`jsonArrayInsert(string $key, string $path, int $index, ...$values);`

**Returns:** The length of the new array (int).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to insert item(s) in a no array.

### `Array Length`

`jsonArrayLength(string $key, string $path = '.');`

**Returns:** The length of the array specified in `path`.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to get the length from a no array.

### `Array Pop`
It removes one element from the array specified in `path` in the `index` position. By default `index` = -1, meaning the 
last element in the array. Negative indexes start counting from the end of the array, positive ones from the beginning.

`jsonArrayPop(string $key, string $path = '.', int $index = -1);`

**Returns:** The element removed from the array.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, array is empty or we try to remove item(s) in a 
no array.

### `Array Trim`
It trims the array specified in `path` from the `start` to `end` positions. Both positions have to be specified:
- if `start` or `stop` params are negative integers they will be counted from the end of the array so if its absolute 
value for both is greater than the length of the array, that means: (abs(start|stop) > len(array), they will be set to 0, 
which will avoid throwing any error
- if `stop` is positive and greater than the length of the array, it will be set to the value of the length of the array, 
stop = len(array)
- if `start` is positive and greater than the length of the array, the result will be an empty array
- if `start` > `stop` the result will be an empty array
- if `start` or `stop` is equal to -1 it will consider at the last position in the array, value of -2 is 
the second-to-last position and so on

`jsonArrayTrim(string $key, int $start, int $stop, string $path = '.');`

**Returns:** The length of the new array after trimming.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to trim in a no array.

## `Object Commands`

### `Object Keys`
It returns an array containing all the key names in an object specified in `path`.

`jsonObjectKeys(string $key, string $path = '.');`

**Returns:** The array of keys in the object. For empty objects, it returns an empty array.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to get the keys for a no object.

### `Object Length`

`jsonObjectLength(string $key, string $path = '.');`

**Returns:** Number of keys in an object specified in `path` (int). For empty objects, instead of returning 0, 
it throws an exception.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, the object is empty or we try to get the keys for 
a no object.

## `String Commands`

### `String Append`
It appends a `value` string to the existing string value in `path`.

`jsonStringAppend(string $key, string $value, string $path = '.');`

**Returns:** New length of the string.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to append to a no string.

### `String Length`

`jsonStringLength(string $key, string $path = '.');`

**Returns:** Length of the string stored in `path`.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to get the length from a no string.

## `General Commands`

### `Delete`
It deletes the value stored in `path`. If `key` or `path` doesn't exist, this command doesn't throw an exception. 
If `path` is root (= '.'), `key` is deleted.

`jsonDelete(string $key, string $path = '.');`

**Returns:** True, otherwise false.

### `Get`
It returns the value stored in `paths`. More than one path can be specified in this command, by providing and array of 
key names. This command doesn't provide some of the features includes in [JSON.GET command in ReJson](https://oss.redislabs.com/redisjson/commands/#jsonget), 
like IDENT, NEWLINE, etc. The reason is that `phpredis-json` was created with the intent to return PHP data structures 
instead of JSON strings, so it doesn't make sense to include params to render the JSON string. Anyway if you still 
want to get values as rendered JSON string, you can use `RedisJsonClient::executeRawCommand('JSON.GET', ...$params)` 
where you can include the `$params` as specified in the link above. 


`jsonGet(string $key, string ...$paths);`

**Returns:** If more than 2 `path(s)` are specified, it returns an array whose `key` is the `path` (with dot prefix, 
like '.colors') and the value the data stored in that `path`, otherwise, by providing just one `path`, returns the value
stored in that path.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist.

### `Get As RESP (Redis Serialization Protocol)`
It returns the [Redis Serialization Protocol (RESP)](https://redis.io/topics/protocol) of a value stored in `path`. 
See [JSON.RESP](https://oss.redislabs.com/redisjson/commands/#jsonresp) for more info.

`jsonGetAsResp(string $key, string $path = '.');`

**Returns:** An array with the RESP of the data stored in the `path`

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist.

### `Increment Num By`
It increments the value stored in `path` by a `number` (int or float). 

`jsonIncrementNumBy(string $key, $number, string $path = '.');`

**Returns:** The result of the arithmetic operation (int or float).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, we try to increment by a number which cannot be 
cast to int or float or we try to increment a NO int|float value stored in `path`.

### `Multiply Num By`
It multiplies the value stored in `path` by a `number` (int or float).

`jsonMultiplyNumBy(string $key, $number, string $path = '.');`

**Returns:** The result of the arithmetic operation (int or float).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, we try to multiply by a number which cannot be 
cast to int or float or we try to multiply a NO int|float value stored in `path`.

### `Memory Usage`
It returns the memory used for the value stored in `path`.

`jsonMemoryUsage(string $key, string $path = '.');`

**Returns:** Bytes of memory used (int).

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist.

### `MultiGet`
It returns the values stored in `path` for each `key` provided in the `$key` array param.

`jsonMultiGet(array $keys, string $path = '.');`

**Returns:** An array where the first element contains the data of `path` for the first key provided, the second element 
contains the data of `path` for the second `key` provided and so on. If a `key` doesn't exists it will return NULL in 
the position of the array. If `path` does not exist, it still returns an array where all the values are NULL.

**Exceptions:** NO exceptions are thrown when `key` or `path` does not exist.

### `Set`
It sets/updates a value in `path` and `key`. For a complete description of this command please visit 
[JSON.SET in RedisJson Documentation](https://oss.redislabs.com/redisjson/commands/#jsonset).

`jsonSet(string $key, $value, string $path = '.', ?string $keyOptions = null);`

**Returns:** True if the command was successfully executed.

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, `path` is not roo ('.'') for new `keys` or the 
specified `NX` or `XX` conditions (if any) were not met.

### `Type`

`jsonType(string $key, string $path = '.');`

**Returns:** It returns the data type of the value stored in `path` (string). You can find the following data types:
- string
- integer
- number
- object
- array
- boolean

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist.
