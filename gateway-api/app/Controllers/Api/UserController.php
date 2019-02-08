<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:55
 */

namespace App\Controllers\Api;
use App\Exception\Http\RpcException;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Enum\UserEnum;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\Group\GroupServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use ServiceComponents\Rpc\User\UserGroupServiceInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserController
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im/user")
 */
class UserController extends BaseController
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
    private $userCacheService;
    /**
     * @Reference("userService")
     * @var UserServiceInterface
     */
    private $userService;
    /**
     * @Reference("userService")
     * @var UserGroupServiceInterface
     */
    private $userGroupService;
    /**
     * 获取群信息 或者获取好友信息
     * @RequestMapping(route="friend/info")
     * @Strings(from=ValidatorFrom::GET,name="type")
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function getInformation($request)
    {
        $data = $request->input();

        //调用用户服务 获取基础信息
        $userRes = $this->userService->getInformation($data['id'],$data['type']);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();

        $info = $userRes['data'];
        return Message::success($info);

    }
    /**
     * 用户退出删除用户相关资源
     * @RequestMapping(route="user/quit")
     * @param Request $request
     */
    public function userQuit($request)
    {
        $token = $request->input("token");
        $user   = $this->userCacheService->getUserByToken($token);
        $info = [
            'user' => $user,
            'token' => $token,
        ];
        if($info)
        {
            // 销毁相关缓存
            $this->delCache($info);

            // 给好友发送离线提醒
            $this->offLine($info);
        }
    }
    /*
    * 销毁个人/群组缓存
    */
    private function delCache($info)
    {
        $fd = $this->userCacheService->getFdByNum($info['user']['number']);
        $this->userCacheService->delTokenUser($info['token']);
        $this->userCacheService->delNumberUserOtherInfo($info['user']['number']);
        $this->userCacheService->delFdToken($fd);
        $this->userCacheService->delFds($fd);

        //调用群组服务 获取群数量
        $groups = $this->groupService->getGroupByCondition(['user_number'=>$info['user']['number']]);
        if($groups)
            foreach ($groups as $val)
                $this->userCacheService->delGroupFd($val->gnumber, $fd);
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user)
    {
        // 从用户服务 获取分组好友
        $userRes = $this->userGroupService->getUserGroupMember($user['id']);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException(['msg' => '获取分组好友失败']);
        $friends = $userRes['data'];

        $server = \Swoft::$server;
        $data = [
            'type'      => 'ws',
            'method'    => 'friendOffLine',
            'data'      => [
                'number'    => $user['user']['id'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val)
            foreach ($val['list'] as $v)
                if ($v['status'])
                {
                    $fd = $this->userCacheService->getFdByNum($v['number']);
                    $server->push($fd, json_encode($data));
                }
    }
    /**
     * 修改用户签名
     * @RequestMapping(route="user/sign",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="sign")
     */
    public function editSignature()
    {
        $sign = request()->query('sign');
        $this->getCurrentUser();

        //调用用户服务 更新签名
        $userRes = $this->userService->updateUserByCondition(['sign' => $sign],['id' => $this->user['id']]);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();

        //更新Redis缓存
        $userRes = $this->userService->getUserByCondition(['id' => $this->user['id']],true);
        $user = $userRes['data'];
        $this->userCacheService->saveTokenToUser(request()->input('token') , $user);
        return Message::success([],'成功');
    }
    /**
     * 查找好友 群
     * @RequestMapping(route="find/total",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="value")
     * @Strings(from=ValidatorFrom::GET,name="type")
     */
    public function findFriendTotal()
    {
        $type = request()->query('type');
        $value = request()->query('value');
        //搜索用户
        if($type == UserEnum::Friend)
        {
            $userRes = $this->userService->searchUser($value);
            if($userRes['code'] != StatusEnum::Success)
                throw new RpcException();
            $res = $userRes['data'];
        }
        else//搜索群组
        {
            //调用群组服务  搜索群组
            $groupRes = $this->groupService->searchGroup($value);
            if($groupRes['code'] != StatusEnum::Success)
                throw new RpcException();
            $res = $groupRes['data'];
        }
        return Message::success(['count' => count($res),'limit' => 16]);
    }
    /**
     * 查找好友 群 统计数量
     * @RequestMapping(route="find/friend",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="value")
     * @Strings(from=ValidatorFrom::GET,name="type")
     * @Strings(from=ValidatorFrom::GET,name="page")
     * @param Request $request
     */
    public function findFriend($request)
    {
        $type = $request->query('type');
        $page = $request->query('page');
        $value = $request->query('value');
        if($type == UserEnum::Friend)
        {
            //搜索用户
            $userRes = $this->userService->searchUser($value );
            if($userRes['code'] != StatusEnum::Success)
                throw new RpcException();
            $res = $userRes['data'];
        }
        else
        {
            //搜索群组
            $groupRes = $this->groupService->searchGroup($value);
            if($groupRes['code'] != StatusEnum::Success)
                throw new RpcException();
            $res = $groupRes['data'];
        }
        return Message::success($res);

    }
}