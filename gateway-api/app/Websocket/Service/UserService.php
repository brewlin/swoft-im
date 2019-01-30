<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\Websocket\Service;

class UserService
{
    const Friend = 'friend';
    const Group = 'group';
    /**
     * 执行任务
     */
    public function keepUser()
    {
        $serv = ServerManager::getInstance()->getServer();
        $allUser = User::getAllUser(['status' => 1]);
        foreach ($allUser as $k => $v)
        {
            $fd = UserCacheService::getFdByNum($v['number']);
            if(!$serv->getClientInfo($fd))
            {
                $token  = UserCacheService::getTokenByNum($v['number']);
                $user   = UserCacheService::getUserByToken($token);
                $info = ['user' => $user,'token' => $token];
                $this->delUserToken($info);
            }
        }
    }
    /**
     * 用户退出删除用户相关资源
     */
    public function delUserToken($info)
    {
        if($info)
        {
            if($info['user'] && $info['token'])
            {
                // 销毁相关缓存
                $this->delCache($info);
                // 给好友发送离线提醒
                $this->offLine($info);
            }
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
}
