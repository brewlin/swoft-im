<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:55
 */

namespace App\Controllers\Api;
use ServiceComponents\Rpc\User\UserModelInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserController
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im/user")
 */
class UserController
{
    /**
     * @Reference("userService")
     * @var UserModelInterface
     */
    private $userModel;
    /**
     * 获取群信息 或者获取好友信息
     * @RequestMapping(route="friend/info")
     * @Strings(from=ValidateFrom::GET,name="type")
     * @Strings(from=ValidateFrom::GET,name="id")
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
            $info = Group::getGroup(['id' => $data['id']] , true);
            $info['type'] = 'group';
        }else
        {
            return $this->error('类型错误');
        }
        return $this->success($info);

    }
    /**
     * 用户退出删除用户相关资源
     */
    public function userQuit()
    {
        $token = $this->request()->getRequestParam("token");
        $user   = UserCacheService::getUserByToken($token);
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
    private function delCache($info){
        $fd = UserCacheService::getFdByNum($info['user']['number']);
        UserCacheService::delTokenUser($info['token']);
        UserCacheService::delNumberUserOtherInfo($info['user']['number']);
        UserCacheService::delFdToken($fd);
        UserCacheService::delFds($fd);
        $groups = GroupMember::getGroups(['user_number'=>$info['user']['number']]);
        if(!$groups->isEmpty()){
            foreach ($groups as $val){
                UserCacheService::delGroupFd($val->gnumber, $fd);
            }
        }
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user){
        // 获取分组好友
        $groups = GroupUser::getAllFriends($user['user']['id']);
        $friends = GroupUserMemberService::getFriends($groups);
        $server = ServerManager::getInstance()->getServer();
        $data = [
            'type'      => 'ws',
            'method'    => 'friendOffLine',
            'data'      => [
                'number'    => $user['user']['id'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val) {
            foreach ($val['list'] as $v){
                if ($v['status']) {
                    $fd = UserCacheService::getFdByNum($v['number']);
                    $server->push($fd, json_encode($data));
                }
            }
        }
    }
    /**
     * 修改用户签名
     */
    public function editSignature()
    {
        (new UserValidate('sign'))->goCheck($this->request());
        $sign = $this->request()->getQueryParam('sign');
        UserModel::updateUser($this->user['id'] ,['sign' => $sign]);
        $user = UserModel::find($this->user['id']);
        UserCacheService::saveTokenToUser($this->request()->getQueryParam('token') , $user);
        return $this->success([],'成功');
    }
    /**
     * 查找好友 群
     */
    public function findFriendTotal()
    {
        (new UserValidate('total'))->goCheck($this->request());
        $type = $this->request()->getQueryParam('type');
        $value = $this->request()->getQueryParam('value');

        if($type == self::Friend)
        {
            //搜索用户
            $res = UserModel::searchUser($value);
            return $this->success(['count' => count($res) ,'limit' => 16]);
        }else
        {
            //搜索群组
            $res = Group::searchGroup($value);
            return $this->success(['count' => count($res) ,'limit' => 16]);
        }
    }
    /**
     * 查找好友 群 统计数量
     */
    public function findFriend()
    {
        (new UserValidate('find'))->goCheck($this->request());
        $type = $this->request()->getQueryParam('type');
        $page = $this->request()->getQueryParam('page');
        $value = $this->request()->getQueryParam('value');
        if($type == self::Friend)
        {
            //搜索用户
            $res = UserModel::searchUser($value , $page);
            return $this->success($res);
        }else
        {
            //搜索群组
            $res = Group::searchGroup($value , $page);
            return $this->success($res);
        }

    }
}