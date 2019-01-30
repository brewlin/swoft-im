<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/15
 * Time: 下午7:50
 */

namespace App\Websocket\Service;


use App\Exception\Websocket\FriendException;
use App\Model\MsgBox;
use App\Model\User as UserModel;
use App\Task\Task;
use App\Task\TaskHelper;
use EasySwoole\Core\Swoole\Task\TaskManager;
use App\Model\Friend as FriendModel;
use App\Model\GroupUserMember;

class FriendService
{
    public function getFriends($arr){
        $res = [];
        foreach ($arr as $val){
            $res[] = self::friendInfo(['id'=>$val]);
        }
        return $res;
    }

    public function friendInfo($where){
        $user = UserModel::where($where)->find();
        $data['id'] = $user['id'];
        $data['avatar'] = $user['avatar'];
        $data['number'] = $user['number'];
        $data['nickname'] = $user['nickname'];
        $data['sign'] = $user['sign'];
        $data['last_login'] = $user['last_login'];
        $data['online']  = UserCacheService::getFdByNum($user['number'])?1:0;   // 是否在线
        return $data;
    }

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data){
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];

        $from_user = FriendService::friendInfo(['number'=>$from_number]);
        $user = FriendService::friendInfo(['number'=>$number]);
        //获取好友请求方的分组
        $msg = MsgBox::getDataById($data['msg_id']);
        $user['groupid'] = $msg['group_user_id'];//好友所在分组

        if($from_user['online']){
            if($check){
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriend', $user))
                    ->getTaskData();
            }else{
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriendFail', $number.'('.$user["nickname"].')'.' 拒绝好友申请'))
                    ->getTaskData();
            }
            $taskClass = new Task($taskData);
            TaskManager::async($taskClass);
        }

//        if($check){
//            if($user['online']){
//                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($number), 'newFriend', $from_user))
//                    ->getTaskData();
//                $taskClass = new Task($taskData);
//                TaskManager::async($taskClass);
//            }
//        }
    }

    /*
     * 检查二人是否是好友关系
     */
    public function checkIsFriend($user1_id, $user2_id){
//        $ids = FriendModel::getAllFriends($user1_id);
        $ids = GroupUserMember::getAllFriends($user1_id);
        if(in_array($user2_id, $ids)){
            return true;
        }
        return false;
    }

}