<?php

namespace App\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Redis\Pool\Config\RedisPoolConfig;

/**
 * RedisCachePoolConfig
 *
 * @Bean()
 */
class RedisCachePoolConfig extends RedisPoolConfig
{
    /**
     * @Value(name="${config.cache.redisCache.db}", env="${REDIS_CACHE_REDIS_DB}")
     * @var int
     */
    protected $db = 0;

    /**
     * @Value(name="${config.cache.redisCache.prefix}", env="${REDIS_Cache_REDIS_PREFIX}")
     * @var string
     */
    protected $prefix = '';
}