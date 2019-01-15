<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 12:30
 */

namespace App\Models\Dao;
use Swoft\Bean\Annotation\Bean;

/**
 * Class UserGroupMemberDao
 * @package App\Models\Dao
 * @Bean()
 */
class UserGroupMemberDao
{
    public function getAllFriends($id)
    {
        return self::where('user_id',$id)->column('friend_id');
    }

    public function newFriend($uId, $friendId , $groupId )
    {
        $model = new self();
        $model->user_id = $uId;
        $model->friend_id = $friendId;
        $model->groupid = $groupId;
        $model->save();
    }
    /**
     * 修改好友备注名
     */
    public function editFriendRemarkName($uid , $friendId , $remark)
    {
        return self::where('user_id' , $uid)
            ->where('friend_id' , $friendId)
            ->update(['remark_name' => $remark]);
    }
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public function moveFriend($uid , $friendId , $groupid)
    {
        return self::where('user_id' , $uid)
            ->where('friend_id' , $friendId)
            ->update(['groupid' => $groupid]);
    }
    public  function removeFriend($uid , $fiendId)
    {
        return self::where('user_id' , $uid)
            ->where('friend_id',$fiendId)
            ->delete();
    }
}