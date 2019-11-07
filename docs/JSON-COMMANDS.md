# Phpredis-JSON Commands (still on developing)
Take a look at [command.php](https://github.com/averias/phpredis-json/blob/master/examples/commands.php) where you could
find examples of the different commands. If you want to execute it you will need a Redis server 4.0+ in 127.0.0.1:6379
with RedisJSON module installed.
 
## `Array Commands`

### `Array Append`
It appends one or more items to the array specified in `path`

`jsonArrayAppend(string $key, string $path, ...$values);`

**Returns:** The length of the new array (int)

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, no `value` is provided or we try to append 
item(s) in a no array 

### `Array Index`
It returns the index of one `value` in the array specified in `path`
By providing `start` and `stop` params we get a slice from de original array:
- if 'start' or 'stop' params are negative integers they will be counted from the end of the array so if its absolute 
value for both is greater than the length of the array, that means: (abs(start|stop) > len(array), they will be set to 0
- if 'stop' is positive and greater than the length of the array, it will be set to the value of the length of the 
array, stop = len(array)
- if 'start' is positive and greater than the length of the array, the result will be an empty array
- if 'start' > 'stop' the result will be an empty array

`jsonArrayIndex(string $key, string $value, string $path = '.', int $start = 0, int $stop = 0);`

**Returns:** The index or -1 if not found of out of range (int)

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try the action on no arrays 

### `Array Insert`
It inserts one or more items to the array specified in `path` in the `index` position

`jsonArrayInsert(string $key, string $path, int $index, ...$values);`

**Returns:** The length of the new array (int)

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to insert item(s) in a no array

### `Array Length`

`jsonArrayLength(string $key, string $path = '.');`

**Returns:** The length of the array specified in `path` 

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to get the length from a no array

### `Array Pop`
It removes one element from the array specified in `path` in the `index` position. By default `index` = 1, meaning the 
last element in the array. Negative indexes start counting from the end of the array, positive ones from the beginning 

`jsonArrayPop(string $key, string $path = '.', int $index = -1);`

**Returns:** The element removed from the array

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, array is empty or we try to remove item(s) in a 
no array

### `Array Trim`
It trims the array specified in `path` from the `start` to `end` positions. Both positions have to be specified:
- if 'start' or 'stop' params are negative integers they will be counted from the end of the array so if its absolute 
value for both is greater than the length of the array, that means: (abs(start|stop) > len(array), they will be set to 0, 
which will avoid to throw any error
- if 'stop' is positive and greater than the length of the array, it will be set to the value of the length of the array, 
stop = len(array)
- if 'start' is positive and greater than the length of the array, the result will be an empty array
- if 'start' > 'stop' the result will be an empty array
- if start or stop are equal to -1 it will considered at the last position in the array, value of -2 is 
the second-to-last position and so on

`jsonArrayTrim(string $key, int $start, int $stop, string $path = '.');`

**Returns:** The length of the new array after trimming

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to remove item(s) in a no array

## `Object Commands`

### `Object Keys`
It returns an array containing all the key names in a object specified in `path` 

`jsonObjectKeys(string $key, string $path = '.');`

**Returns:** The array of keys in the object, for empty objects, it returns an empty array

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist or we try to get the keys for a no object

### `Object Length`
Number of keys in a object specified in `path` (int)

`jsonObjectLength(string $key, string $path = '.');`

**Returns:** Number of keys in a object specified in `path` (int), for empty objects, instead of returning 0, 
it throws an exception

**Exceptions:** `ResponseException` if `key` or `path` doesn't exist, object is empty or we try to get the keys for 
a no object

## `String Commands`
TBD
### `String Append`

### `String Length`

## `Other Commands`
TBD
### `Delete`

### `Get As RESP (REdis Serialization Protocol)`

### `Get`

### `Increment Num By / Multiply Num By`

### `Memory Usage`

### `MultiGet`

### `Set`

### `Type`