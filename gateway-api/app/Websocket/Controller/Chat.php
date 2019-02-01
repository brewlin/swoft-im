<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2018/7/21
 */

namespace App\Websocket\Controller;


use App\Exception\Http\RpcException;
use App\Exception\Http\SockException;
use App\Models\Service\FriendService;
use App\Websocket\Service\ChatService;
use ServiceComponents\Common\Common;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;

class Chat extends BaseWs
{
    /*
     * 处理个人聊天
     * @param number
     * @param data
     *
     * 1. 验证用户是否存在，是否在线
     * 2. 检查是否是好友关系
     * 3. 异步给双方发送消息，做标记是自己的还是对方发的
     * 4. 异步存储消息记录
     */
    public function personalChat()
    {
        $content = $this->content;
        $user = $this->getUserInfo();
        $userRes = $this->rpcDao->userService('getUserByCondition',['id' => $content['id']]);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $to_number = $userRes['number'];
        $data = Common::security($content['data']);
        /**
         * 验证好友在线情况
         */
        $to_user = $this->onlineValidate($content['id']);
        // 异步发送消息的数据结构
        $chat_data = [
            'from'  => $user,
            'to'    => $to_user,
            'data'  => $data,
            'is_read' => 1
        ];
        if(isset($to_user['errorCode']))
        {
            //不在线直接存储消息后退出
            $chat_data['to'] = ['user' => ['id' => $content['id']]];
            $chat_data['is_read'] = 0;

            // 异步存储消息
            App::getBean(ChatService::class)->savePersonalMsg($chat_data);
            $this->sendMsg(['data' => $to_user['msg']]);
            return;
        }
        // 查二者是否已经是好友
        $isFriend = App::getBean(FriendService::class)->checkIsFriend($user['user']['id'], $to_user['user']['id']);
        if(!$isFriend)
            throw new SockException(['msg' => '非好友状态']);
        App::getBean(ChatService::class)->sendPersonalMsg($chat_data);

        // 异步存储消息
        App::getBean(ChatService::class)->savePersonalMsg($chat_data);
    }

    /*
     * 处理群组聊天
     * @param gnumber
     * @param data
     *
     * 1. 查询该组是否存在
     * 2. 查询此人是否在组中
     * 3. 异步给组内所有人发送消息，做标记是自己的还是对方发的
     * 4. 异步存储消息记录
     */
    public function groupChat()
    {
        $content = $this->content;
        $user = $this->getUserInfo();
        $gnumber = isset($content['gnumber'])?$content['gnumber']:"";
        $data =  Common::security($content['data']);

        $is_in = $this->rpcDao->groupService('getGroupByCondition',['user_number'=>$user['user']['number'], 'gnumber'=>$gnumber]);
        if($is_in['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '群服务信息调用失败']);


        // 异步发送消息
        $chat_data = [
            'user'      => $user,
            'gnumber'   => $gnumber,
            'data'      => $data
        ];

        // 发送群组消息
        App::getBean(ChatService::class)->sendGroupMsg($chat_data);
        // 异步存储消息
        App::getBean(ChatService::class)->saveGroupMsg($chat_data);
    }
}