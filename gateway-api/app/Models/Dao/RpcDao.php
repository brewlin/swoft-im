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
use ServiceComponents\Rpc\Msg\MsgServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserGroupServiceInterface;
use ServiceComponents\Rpc\User\UserRecordServiceInterface;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
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
    private $groupService;
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCache;
    /**
     * @Reference("userService")
     * @var UserServiceInterface
     */
    private $userService;
    /**
     * @Reference("msgService")
     * @var MsgServiceInterface
     */
    private $msgService;
    /**
     * @Reference("userService")
     * @var UserGroupMemberServiceInterface
     */
    private $userGroupMemberService;
    /**
     * @Reference("userService")
     * @var UserGroupServiceInterface
     */
    private $userGroupService;
    /**
     * @Reference("userService")
     * @var UserRecordServiceInterface
     */
    private $userRecordService;
    /**
     * Rpc 里无法使用Rpc客户端，所以采用折中的办法，单独一个bean储存Rpc连接池
     * @param $function
     * @param array ...$param
     */
    public function userCache($function ,...$param)
    {
        return $this->userCache->$function(...$param);
    }
    public function userRecordService($function,...$param)
    {
        return $this->userRecordService->$function(...$param);
    }
    public function userGroupService($function,...$param)
    {
        return $this->userGroupService->$function(...$param);
    }
    public function userGroupMemberService($function ,...$param)
    {
        return $this->userGroupMemberService->$function(...$param);
    }
    public function msgService($function,...$param)
    {
        return $this->msgService->$function(...$param);
    }
    public function userService($function,...$param)
    {
        return $this->userService->$function(...$param);
    }
    public function groupService($function,...$param)
    {
        return $this->groupService->$function(...$param);
    }

}