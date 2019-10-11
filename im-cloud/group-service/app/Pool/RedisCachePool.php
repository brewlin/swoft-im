<?php
/**
 * This file is part of Swoft.
 *
 * @link    https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Swoft\Redis\Pool\RedisPool;
use App\Pool\Config\RedisCachePoolConfig;

/**
 * DemoRedisPool
 *
 * @Pool("redisCache")
 */
class RedisCachePool extends RedisPool
{
    /**
     * @Inject()
     * @var RedisCachePoolConfig
     */
    public $poolConfig;
}