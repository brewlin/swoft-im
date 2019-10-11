<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2019/1/15
 * Time: 下午9:33
 */

namespace App\Websocket\Service;


use App\Models\Dao\RpcDao;
use App\WebSocket\Common\TaskHelper;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Task\Task;

/**
 * Class GroupUserMemberService
 * @package App\Websocket\Service
 * @Bean()
 */
class GroupUserMemberService
{
    public function getFriends($arr)
    {
        foreach ($arr as &$group)
        {
            foreach ($group['list'] as $k => &$friend)
            {
                //检查是否有昵称存在 有则替换当前的昵称
                if(!empty($friend['remark_name']))
                {
                    $name = $friend['remark_name'];
                    $group['list'][$k] = self::friendInfo(['id' => $friend['friend_id']]);
                    $group['list'][$k]['username'] = $name;
                }else
                {
                    $group['list'][$k] = self::friendInfo(['id' => $friend['friend_id']]);
                }
            }
        }
        return $arr;
    }
    public function newFriends($data ,$currentUid)
    {
        //添加自己的好友
        $rpcDao = App::getBean(RpcDao::class);
        $rpcDao->userGroupMemberService('newFriend',$currentUid ,$data['friend_id'] ,$data['group_user_id']);
        //请求方添加好友
        //获取消息里的数据
        $friend = ($rpcDao->msgService('getDataById',$data['msg_id']))['data'];
        $rpcDao->userGroupMemberService('newFriend',$friend['from'] , $friend['to'] ,$friend['group_user_id']);
    }
    public function friendInfo($where)
    {
        $rpcDao = App::getBean(RpcDao::class);
        $user = ($rpcDao->userService('getUserByCondition',$where,true))['data'];
        $user['status']  = $rpcDao->userCache('getTokenByNum',$user['number'])?'online':'offline';   // 是否在线
        return $user;
    }

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data)
    {
        $rpcDao = App::getBean(RpcDao::class);
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];
        $from_user = ($rpcDao->userGroupMemberService('friendInfo',['number'=>$from_number]))['data'];
        $user = ($rpcDao->userGroupMemberService('friendInfo',['number'=>$number]))['data'];


        if($from_user['online'])
            if($check)
            {
                $taskData = TaskHelper::getTaskData('newFriend',$user,$rpcDao->userCache->getFdByNum($from_number));
                Task::deliver('SyncTask','sendMsg',[$taskData],Task::TYPE_ASYNC);
            }else
            {
                $taskData = TaskHelper::getTaskData('newFriendFail',$number.'('.$user["nickname"].')'.' 拒绝好友申请',$rpcDao->userCache->getFdByNum($from_number));
                Task::deliver('SyncTask','sendMsg',[$taskData],Task::TYPE_ASYNC);
            }

        if($check)
            if($user['online'])
            {
                $taskData = TaskHelper::getTaskData('newFriend',$from_user,$rpcDao->userCache->getFdByNum($number));
                Task::deliver('SyncTask','sendMsg',[$taskData],Task::TYPE_ASYNC);
            }
    }

    /*
     * 检查二人是否是好友关系
     */
    public function checkIsFriend($user1_id, $user2_id)
    {
        $rpcDao = App::getBean(RpcDao::class);
        $ids = ($rpcDao->userGroupMemberService('getAllFriends',$user1_id))['data'];
        if(in_array($user2_id, $ids))
            return true;
        return false;
    }

}