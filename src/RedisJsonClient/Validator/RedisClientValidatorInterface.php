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

interface RedisClientValidatorInterface
{
    /**
     * @param string $version
     * @return bool
     */
    public function isValidRedisVersion(string $version): bool;

    /**
     * @param array $moduleListCommandResponse
     * @return bool
     */
    public function isRedisJsonModuleInstalled(array $moduleListCommandResponse): bool;
}
