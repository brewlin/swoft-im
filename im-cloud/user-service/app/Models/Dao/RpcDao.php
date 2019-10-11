<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:43
 */

namespace App\Models\Dao;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\Group\GroupServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class RpcDao
 * @package App\Models\Dao
 * @Bean()
 */
class RpcDao
{
    /**
     * @Reference("groupService")
     * @var GroupServiceInterface
     */
    public $groupService;
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    public $userCache;

    /**
     * Rpc 里无法使用Rpc客户端，所以采用折中的办法，单独一个bean储存Rpc连接池
     * @param $function
     * @param array ...$param
     */
    public function userCache($function ,...$param)
    {
        $this->userCache->$function(...$param);
    }

}