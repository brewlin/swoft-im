<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2019/1/17
 * Time: 下午5:22
 */
namespace App\Websocket\Controller;
use App\Exception\Http\SockException;
use App\Websocket\Service\ChatService;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;

/**
 * Class OnOpen
 * @package App\WebsocketController
 */
class OnOpen extends BaseWs
{
    /*
     * 用户连线后初始化
     * 传参：token
     * 1. 获取用户 fd
     * 2. 初始化所有相关缓存
     * 3. 向所有好友发送上线提醒
     * 4. 向所有群聊发送上线提醒
     */
    public function init()
    {
        $user = $this->getUserInfo();
        if(!$user)
            throw new SockException(['msg' => 'token异常']);

        //判断是否有其他地方已登陆
        $userFd = $this->rpcDao->userCache('getFdByNum',$user['user']['number']);
        if($userFd != (int)$user['fd'])
        {
            $this->push($userFd , ['type'=>'ws','method'=> 'belogin','data'=> 'logout']);
        }
        //初始化所有相关缓存
        $this->saveCache($user);

        // 查出所有好友，查所有好友的在线状态，向所有好友发送上线提醒
        $this->sendOnlineMsg($user);


        //检查离线消息
        $this->checkOfflineRecord($user);

        $this->sendMsg(['method'=>'initok','data'=>$user['user']]);
    }
    public function push($fd,$data)
    {
        $server = \Swoft::$server->getServer();
        if($fd)
            if($server->getClientInfo($fd))
                $server->push($fd,json_encode($data));
    }

    /**
     * @param $user
     * 检查离线消息
     */
    public function checkOfflineRecord($self)
    {
        $record = $this->rpcDao->userRecordService('getAllNoReadRecord',$self['user']['id']);
        if($record['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '湖区聊天消息失败']);

        $sendData = [];
        $data['to'] = $self;
        foreach ($record as $k => $v)
        {
            $user['user'] = $v['user'];
            $data['from'] = $user;
            $data['data'] = $v['data'];
            $sendData[] = $data;
        }
        App::getBean(ChatService::class)->sendOfflineMsg($this->fd,$sendData);
    }
    private function saveCache($user)
    {
        // 更新用户在线状态缓存（ 添加 fd 字段 ）
        $this->rpcDao->userCache('saveNumToFd',$user['user']['number'], $user['fd']);
        // 添加 fd 与 token 关联缓存，close 时可以销毁 fd 相关缓存
        $this->rpcDao->userCache('saveTokenByFd',$user['fd'], $user['token']);

        // 查找用户所在所有组，初始化组缓存
        $groupRes = $this->rpcDao->groupService('getGroup',['user_id' => $user['user']['id']]);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '获取群数据失败']);
        $groups = $groupRes['data'];
        if($groups)
        {
            foreach ($groups as $val)
            {
                $this->rpcDao->userCache('setGroupFds',$val->gnumber, $user['fd']);
            }
        }
    }
    /*
     * 发送上线通知
     */
    private function sendOnlineMsg($user)
    {
        // 获取分组好友
        $groupRes = $this->rpcDao->userGroupMemberService('getAllFriends',$user['user']['id']);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '获取好友数据失败']);
        $friends = $groupRes['data'];
        $data = [
            'type'      => 'ws',
            'method'    => 'friendOnLine',
            'data'      => [
                'number'    => $user['user']['id'],
                'nickname'  => $user['user']['nickname'],
            ]
        ];
        foreach ($friends as $val)
        {
            foreach ($val['list'] as $v)
            {
                if($v['status'])
                {
                    $fd = $this->rpcDao->userCache('getFdByNum',$v['number']);
                    $this->push($fd,$data);
                }
            }
        }
    }
}