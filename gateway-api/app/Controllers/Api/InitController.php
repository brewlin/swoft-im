<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:50
 */

namespace App\Controllers\Api;
use App\Exception\Http\RpcException;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use ServiceComponents\Rpc\User\UserGroupServiceInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class InitController
 * @package App\Controllers\Api
 * @Controller("api/im")
 */
class InitController
{
    /**
     * @Reference("userService")
     * @var UserCacheInterface
     */
    private $userCache;
    /**
     * @Reference("groupService")
     * @var GroupServiceInterface
     */
    private $groupService;
    /**
     * @Reference("userService")
     * @var UserGroupServiceInterface
     */
    private $userGroupServie;
    /**
     * @RequestMapping(route="init")
     * @param Request $request
     */
    public function initIm($request)
    {
        //从缓存服务 获取自己信息
        $token = $request->input('token');
        $user = $this->userCache->getUserByToken($token);
        $user['status'] = 'online';

        // 从用户服务 获取分组好友
        $userRes = $this->userGroupServie->getUserGroupMember($user['id']);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException(['msg' => '获取分组好友失败']);
        $data = $userRes['data'];

        //从群组服务 获取群组信息
        $groupRes = $this->groupService->getGroupListByNumber($user['number']);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException(['msg' => '获取群组失败']);
        $groups = $groupRes['data'];

        return Message::success(['mine' => $user ,'friend' => $data, 'group' => $groups?$groups:[]]);
    }
}