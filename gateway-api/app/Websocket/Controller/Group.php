<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2018/10/18
 */

namespace App\Websocket\Controller;

use App\Exception\Http\SockException;
use App\Websocket\Enum\MsgBoxEnum;
use App\Websocket\Service\GroupService;
use ServiceComponents\Common\Common;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;
use Swoft\Task\Task;

class Group extends BaseWs
{

    /*
     * 创建群组
     * 1. 验证此人创建了多少群组，不可超过3个
     * 2. 创建群号
     * 3. 保存群信息，此人加入该群
     * 4. 创建缓存
     * 5. 异步返回创建的群
     */
    public function create()
    {
        $content = $this->content;
        $user = $this->getUserInfo();
        $gname = $content['gname'];
        $ginfo = isset($content['ginfo'])?$content['ginfo']:"";

        if(empty($gname))
            throw new SockException(['msg' => '参数异常']);

        $groupRes = $this->rpcDao->groupService('getGroup',['user_number'=>$user['user']['number']]);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['调用群组服务失败']);
        $count = count($groupRes['data']);
        if($count>=3)
        throw new SockException(['msg' => '创建群组已达上限']);

        // 生成唯一群号
        $number = Common::generate_code(8);

        // 保存群信息，并加入群
        $group_data = [
            'gnumber'       => $number,
            'user_number'   => $user['user']['number'],
            'ginfo'         => $ginfo,
            'gname'         => $gname
        ];
        $groupRes = $this->rpcDao->groupService('createGroup',$group_data,$number,$user['user']['number']);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '创建群失败']);
        $gid = $groupRes['data'];
        // 创建缓存
        $this->rpcDao->userCache('setGroupFds',$number,$this->fd);

        // 异步通知
        $g_info = [
            'gname'  => $gname,
            'ginfo'  => $ginfo,
            'gnumber'=> $number,
            'gid'     => $gid,
        ];
        App::getBean(GroupService::class)->sendNewGroupInfo($g_info,$user);
    }

    /*
     * 加入群组
     * 1. 查询群组是否存在
     * 2. 查询是否已在群组中
     * 3. 写入数据库，存缓存
     * 4. 发送群组信息
     */
    public function sendJoinGroupReq()
    {
        $content = $this->content;
        $user = $this->getUserInfo();
        $id = $content['id'];
        $gnumber = $content['gnumber'];

        //查询群组是否存在
        $groupRes = $this->rpcDao->groupService('getGroup',['gnumber'=>$gnumber], true);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '调用服务失败']);
        $res = $groupRes['data'];
        if(!$res)
            throw new SockException(['msg' => '群组不存在']);

        // 查询是否在群组中
        $groupRes = $this->rpcDao->groupService('getGroupMemberByCondition',['user_number'=>$user['user']['number'], 'gnumber'=>$gnumber]);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '调用服务失败']);

        $is_in = $groupRes['data'];
        if(!$is_in)
            throw new SockException(['msg' =>'不在此群中']);

        // 准备发送请求的数据
        $data = [
            'method'    => 'groupRequest',
            'data'      => [
                'from'  => $user['user']
            ]
        ];
        //获取群主
        $groupRes = $this->rpcDao->groupService('getGroupOwnById',$id);
        if($groupRes['code'] != StatusEnum::Success)
            throw new SockException(['msg' => '调用服务失败']);

        $toUser = $groupRes['data'];
        $toId = $toUser['user']['id'];
        //写入msgbox记录
        $msgBox = [
            'type' => MsgBoxEnum::AddGroup,
            'from' => $user['user']['id'],
            'to' => $toId,
            'send_time' => time(),
            'remark' => $content['remark'],
            'group_user_id' => $id,
        ];
        $msgRes = $this->rpcDao->msgService('addMsgBox',$msgBox);
        if($msgBox['code'] != StatusEnum::Success)
            throw new SockException(['调用服务失败']);
        $msgId = $msgRes['data'];
        $data['data']['from']['msg_id'] = $msgId;
        $data['data']['from']['gnumber'] = $gnumber;
        $data['data']['from']['gid'] = $id;

        // 异步加群要求
        $fd = $this->rpcDao->userCache('getFdByNum',$toUser['user']['number']);
        $taskData = [
                'fd'        => $fd,
                'data'      => $data
        ];
        Task::deliver('SyncTask','sendMsg',$taskData,Task::TYPE_ASYNC);
        $this->sendMsg(['data'=>'加群请求已发送！']);

    }
    /**
     * 群主处理加群请求
     */
    public function doJoinGroupReq()
    {
        $content = $this->content;

        //申请人的信息
        $userRes = $this->rpcDao->userService('getUserByCondition',['id' => $content['from_id']],true);
        $fromUser = $userRes['data'];
        $check = $content['check'];
        $user = $this->getUserInfo();
        $gid = $content['gid'];
        $groupRes = $this->rpcDao->groupService('getGroup',['id' => $gid],true);
        $groupInfo = $groupRes['data'];
        $gnumber = $groupInfo['gnumber'];

        // 若同意，
            //添加群记录记录，
            //异步通知双方，
            //更新消息状态
        //若不同意，在线则发消息通知
        if($check)
        {
            $this->rpcDao->msgService('updateById',$content['msg_id'],['type' => $content['msg_type'] ,'status' => $content['status'] ,'read_time' => time()]);

            //判断此人是否在群里
            $groupRes = $this->rpcDao->groupService->getGroupMemberByCondition(['gnumber' => $gnumber,'user_id' => $fromUser['id']],true);

            if($groupRes['data'])
                throw new SockException(['msg' => '用户已在此群中']);

            $this->rpcDao->groupService('newGroupMember',['gnumber' => $gnumber,'user_number' => $fromUser['number'],'status' => 1]);
        }else
        {
            //更新为拒绝
            $this->rpcDao->msgService('updateById',$content['msg_id'] , ['type' => $content['msg_type'] ,'status' => 4 ,'read_time' => time()]);
        }
        // 异步通知双方
        $data  = [
            'id'            => $gid,
            'avatar'         => $groupInfo['avatar'],
            'groupname'     => $groupInfo['groupname'],
            'gnumber'       => $gnumber,
            'type'          => 'group'

        ];
        App::getBean(GroupService::class)->doReq($fromUser['number'],$check,$data);
        $server = \Swoft::$server;
        $server->push($this->rpcDao->userCache('getFdByNum',$fromUser['number']) , json_encode(['type'=>'ws','method'=> 'ok','data'=> '加入群-'.$groupInfo['groupname'].'-成功!']));
        // 创建缓存
        $this->rpcDao->userCache('setGroupFds',$gnumber, $user['fd']);

    }
    /*
     * 群组列表
     */
    public function getGroups()
    {
        $user = $this->getUserInfo();
        $groupRes = $this->rpcDao->groupService('getGroupMemberByCondition',['user_id'=>$user['user']['id']]);
        $groups = $groupRes['data'];
       // $this->sendMsg(['method'=>'groupList','data'=>$groups]);
    }
}