<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/7/28
 * Time: 23:21
 */

namespace App\Websocket\Service;

use App\Models\Dao\RpcDao;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;

/**
 * Class UserService
 * @package App\Websocket\Service
 * @Bean()
 */
class UserService
{
    const Friend = 'friend';
    const Group = 'group';
    /**
     * 执行任务
     */
    public function keepUser()
    {
        $serv = \Swoft::$server;
        $rpcDao = App::getBean(RpcDao::class);
        $allUser = ($rpcDao->userService('getAllUser'))['data'];
        foreach ($allUser as $k => $v)
        {
            $fd = $rpcDao->userCache('getFdByNum',$v['number']);
            if(!$serv->getClientInfo($fd))
            {
                $token = $rpcDao->userCache('getTokenByNum',$v['number']);
                $user = $rpcDao->userCache('getUserByToken',$token);
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
    private function delCache($info)
    {
        $rpcDao = App::getBean(RpcDao::class);

        $fd = $rpcDao->userCacheService('getFdByNum',$info['user']['number']);
        $rpcDao->userCacheService('delTokenUser',$info['token']);
        $rpcDao->userCacheService('delNumberUserOtherInfo',$info['user']['number']);
        $rpcDao->userCacheService('delFdToken',$fd);
        $rpcDao->userCacheService('delFds',$fd);
        $groupRes = $rpcDao->groupService('getGroup',['user_number'=>$info['user']['number']]);
        $groups = $groupRes['data'];
        if($groups)
            foreach ($groups as $val)
                $rpcDao->userCacheService('delGroupFd',$val->gnumber, $fd);
    }

    /*
     * 给在线好友发送离线提醒
     */
    private function offLine($user)
    {
        $rpcDao = App::getBean(RpcDao::class);
        // 获取分组好友
        $userRes = $rpcDao->userGroupMemberService('getAllFriends',$user['user']['id']);
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
                    $fd = $rpcDao->userCache('getFdByNum',$v['number']);
                    $server->push($fd, json_encode($data));
                }
    }
}
