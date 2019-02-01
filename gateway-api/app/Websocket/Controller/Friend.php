<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2018/9/21
 * Time: 下午5:41
 */

namespace App\Websocket\Controller;

use App\Exception\Http\SockException;
use App\WebSocket\Common\TaskHelper;
use App\Websocket\Enum\MsgBoxEnum;
use App\Websocket\Service\FriendService;
use App\Websocket\Service\MsgBoxServer;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;
use Swoft\Task\Task;

class Friend extends BaseWs
{
    /*
     * 发送好友请求
     * 1. 查看当前用户是存在/是否在线
     * 2. 发送好友请求
     */
    public function sendReq()
    {
        $content = $this->content;
        $user = $this->getUserInfo();
        $toId = $content['id'];
        $toUser = $this->onlineValidate($toId);
        if(isset($to_user['errorCode']))
            throw new SockException();
        // 不可添加自己好友
        if($user['user']['number'] == $toId)
            throw new SockException(['msg' => '不可添加自己为好友']);

        // 查二者是否已经是好友
        $isFriend = App::getBean(FriendService::class)->checkIsFriend($user['user']['id'], $toUser['user']['id']);
        if($isFriend)
            throw new SockException(['msg' => '不可重复添加好友']);

        // 存储请求状态
        $this->rpcDao->userCache('saveFriendReq',$user['user']['number'], $toUser['user']['number']);

        // 准备发送请求的数据
        $data = [
            'method'    => 'friendRequest',
            'data'      => [
                'from'  => $user['user']
            ]
        ];
        //写入msgbox记录
        $msgBox = [
            'type' => MsgBoxEnum::AddFriend,
            'from' => $user['user']['id'],
            'to' => $toId,
            'send_time' => time(),
            'remark' => $content['remark'],
            'group_user_id' => $content['group_user_id'],
        ];
        //调用消息存储服务
        $msgRes = $this->rpcDao->msgService('addMsgBox',$msgBox);
        if($msgRes['code'] != StatusEnum::Success)
            throw new SockException(['消息存储失败']);
        $msgId = $msgRes['data'];

        $data['data']['from']['msg_id'] = $msgId;
        // 异步发送好友请求
        $fd = $this->rpcDao->userCache('getFdByNum',$toUser['user']['number']);
        $taskData = [
                'fd'        => $fd,
                'data'      => $data
        ];
        Task::deliver('SyncTask','sendMsg',$taskData,Task::TYPE_ASYNC);
    }

    /*
     * 处理好友请求
     * @param number 对方号码
     * @param res    是否同意，1同意，0不同意
     */
    public function doReq()
    {
        $content = $this->content;
        $userRes = $this->rpcDao->userService('getUserByCondition',['id' => $content['friend_id']],true);
        if($userRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '用户服务调用失败']);
        $fromUser = $userRes['data'];
        $check = $content['check'];
        $user = $this->getUserInfo();

        // 缓存校验，删除缓存，成功表示有该缓存记录，失败则没有
        $cache = $this->rpcDao->userCache('delFriendReq',$fromUser['number']);
        if(!$cache)
            throw new SockException(['msg' => '好友请求失败']);

        // 若同意，
        //添加好友记录，
        //加入对方好友队列
        //异步通知双方，
        //更新消息状态
        //若不同意，在线则发消息通知
        if($check)
        {
            App::getBean(MsgBoxServer::class)->updateStatus($content,$user['user']['id']);
            $this->rpcDao->userGroupMemberService('newFriends',$content,$userRes['user']['id']);
        }else
        {
            //更新为拒绝
            $this->rpcDao->msgService('updateById',$content['msg_id'] , ['type' => $content['msg_type'] ,'status' => $content['status'] ,'read_time' => time()]);
        }

        // 异步通知双方
        $data  = [
            'from_number'   => $fromUser['number'],
            'number'        => $user['user']['number'],
            'check'         => $check,
            'msg_id'        => $content['msg_id'],
        ];
        App::getBean(FriendService::class)->doReq($data);
    }

    /*
     * 获取好友列表
     */
    public function getFriends()
    {
        $user = $this->getUserInfo();
        $userRes = $this->rpcDao->userGroupService('getAllFriends',$user['user']['id']);
        if($userRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '调用获取好友服务失败']);
        $data = $userRes['data'];
        //$this->sendMsg(['method'=>'getFriends','data'=>$data]);
    }
}
