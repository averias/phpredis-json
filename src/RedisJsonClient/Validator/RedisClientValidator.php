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

namespace Averias\RedisJson\Validator;

use Averias\RedisJson\Enum\Module;
use Averias\RedisJson\Enum\Version;

class RedisClientValidator implements RedisClientValidatorInterface
{
    /**
     * @param string $version
     * @return bool
     */
    public function isValidRedisVersion(string $version): bool
    {
        $version .= '.0';
        return (float) $version >= (float) Version::REDIS_JSON_CLIENT_4X0;
    }

    /**
     * @param array $moduleListCommandResponse
     * @return bool
     */
    public function isRedisJsonModuleInstalled(array $moduleListCommandResponse): bool
    {
        foreach ($moduleListCommandResponse as $group) {
            if (in_array(Module::REDIS_JSON_MODULE_NAME, $group)) {
                return true;
            }
        }

        return false;
    }
}
