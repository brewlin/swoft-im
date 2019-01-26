<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:55
 */

namespace App\Controllers\Api;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\UserEnum;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
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
     * @Reference("userService")
     * @var UserModelInterface
     */
    private $userModel;
    /**
     * @Reference("groupService")
     * @var GroupModelInterface
     */
    private $groupModel;
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCacheService;
    /**
     * @Reference("groupService")
     * @var GroupMemberModelInterface
     */
    private $groupMemberModel;
    /**
     * @Reference("userService")
     * @var UserGroupModelInterface
     */
    private $userGroupModel;
    /**
     * @Reference("userService")
     * @var UserGroupMemberServiceInterface
     */
    private $userGroupMemberService;
    /**
     * 获取群信息 或者获取好友信息
     * @RequestMapping(route="friend/info")
     * @Strings(from=ValidatorFrom::GET,name="type")
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function getInformation($request)
    {
        //type friend 就获取好友信息 type为group则获取群信息
        $data = $request->input();
        if($data['type'] == 'friend')
        {
            $info = $this->userModel->getUser(['id' => $data['id']]);
            $info['type'] = 'friend';
        }else if($data['type'] == 'group')
        {
            $info = $this->groupModel->getGroup(['id' => $data['id']] , true);
            $info['type'] = 'group';
        }else
        {
            return Message::error('','类型错误');
        }
        return Message::sucess($info);

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
        $groups = $this->groupMemberModel->getGroups(['user_number'=>$info['user']['number']]);
        if($groups)
            foreach ($groups as $val)
                $this->userCacheService->delGroupFd($val->gnumber, $fd);
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user){
        // 获取分组好友
        $groups = $this->userGroupModel->getAllFriends($user['user']['id']);
        $friends = $this->userGroupMemberService->getFriends($groups);
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
        $this->userModel->updateUser($this->user['id'] ,['sign' => $sign]);
        $user = $this->userModel->getUser(['id' => $this->user['id']]);
        $this->userCacheService->saveTokenToUser(request()->input('token') , $user);
        return Message::sucess([],'成功');
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
            $res = $this->userModel->searchUser($value);
        else//搜索群组
            $res = $this->groupModel->searchGroup($value);
        return Message::sucess(['count' => count($res),'limit' => 16]);
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
            //搜索用户
            $res = $this->userModel->searchUser($value , $page);
        else
            //搜索群组
            $res = $this->groupModel->searchGroup($value , $page);
        return Message::sucess($res);

    }
}