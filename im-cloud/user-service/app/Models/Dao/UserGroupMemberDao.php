<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 12:30
 */

namespace App\Models\Dao;
use App\Models\Entity\UserGroup;
use App\Models\Entity\UserGroupMember;
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
        return UserGroupMember::findAll(['user_id' => $id])->getResult();
       // return self::where('user_id',$id)->column('friend_id');
    }

    public function newFriend($uId, $friendId , $groupId )
    {
        return (new UserGroupMember())->fill(['user_id' => $uId,'friend_id' => $friendId,'user_group_id' => $groupId])
                                       ->save()
                                       ->getResult();
    }
    /**
     * 修改好友备注名
     */
    public function editFriendRemarkName($uid , $friendId , $remark)
    {
        return UserGroupMember::updateOne(['remar_name' => $remark],['user_id' => $uid,'friend_id' =>$friendId])->getResult();
    }
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public function moveFriend($uid , $friendId , $groupid)
    {
        return UserGroupMember::updateOne(['groupid' => $groupid],['user_id' => $uid,'friend_id' => $friendId])->getResult();
    }
    public  function removeFriend($uid , $fiendId)
    {
        return UserGroupMember::deleteOne(['user_id' => $uid,'friend_id' => $fiendId])->getResult();
    }
}