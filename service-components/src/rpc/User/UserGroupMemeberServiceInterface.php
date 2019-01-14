<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/14
 * Time: 22:28
 */

namespace ServiceComponents\Rpc\User;


interface UserGroupMemeberServiceInterface
{
    public static function getFriends($arr){
        foreach ($arr as &$group){
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
    public static function newFriends($data ,$currentUid)
    {
        $userMember = new GroupUserMember();
        //添加自己的好友
        $userMember::newFriend($currentUid ,$data['friend_id'] ,$data['group_user_id']);
        //请求方添加好友
        //获取消息里的数据
        $friend = MsgBox::getDataById($data['msg_id']);
        $userMember::newFriend($friend['from'] , $friend['to'] ,$friend['group_user_id']);
    }
    public static function friendInfo($where){
        $user = UserModel::where($where)->find();
        $user['status']  = UserCacheService::getTokenByNum($user['number'])?'online':'offline';   // 是否在线
        return $user;
    }

    // 处理接收或拒绝添加好友的通知操作
    public static function doReq($data){
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];

        $from_user = FriendService::friendInfo(['number'=>$from_number]);
        $user = FriendService::friendInfo(['number'=>$number]);


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

        if($check){
            if($user['online']){
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($number), 'newFriend', $from_user))
                    ->getTaskData();
                $taskClass = new Task($taskData);
                TaskManager::async($taskClass);
            }
        }
    }

    /*
     * 检查二人是否是好友关系
     */
    public static function checkIsFriend($user1_id, $user2_id){
        $ids = FriendModel::getAllFriends($user1_id);
        if(in_array($user2_id, $ids)){
            return true;
        }
        return false;
    }
}