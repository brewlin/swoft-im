<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2019/2/01
 * Time: 下午9:12
 */

namespace App\Websocket\Service;



use App\Models\Dao\RpcDao;
use App\WebSocket\Common\TaskHelper;
use Swoft\App;
use Swoft\Task\Task;

class FriendService
{
    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data)
    {
        $rpcDao = App::getBean(RpcDao::class);
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];
        $from_user = ($rpcDao->userService('getUserByCondition',['number'=>$from_number],true))['data'];
        $user = ($rpcDao->userService('getUserByCondition',['number' => $number],true))['data'];
        //获取好友请求方的分组
        $msg = ($rpcDao->msgService('getDataById',$data['msg_id']))['data'];
        $user['groupid'] = $msg['group_user_id'];//好友所在分组

        if($from_user['online'])
        {
            if($check)
            {
                $taskData = TaskHelper::getTaskData('newFriend',$user,$rpcDao->userCache->getFdByNum($from_number));
                Task::deliver('SyncTask','sendMsg',$taskData);
            }else
            {
                $taskData = TaskHelper::getTaskData('newFriendFail',[],$rpcDao->userCache->getFdByNum($from_number));
                Task::deliver('SyncTask',$number.'('.$user["nickname"].')'.' 拒绝好友申请','sendMsg',$taskData);
            }
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