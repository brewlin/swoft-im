<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 19:48
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserGroupMemberModelInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserGroupMemberModelInterface
{
    public function getAllFriends($id);


    public function newFriend($uId, $friendId , $groupId );

    /**
     * 修改好友备注名
     */
    public function editFriendRemarkName($uid , $friendId , $remark);
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public function moveFriend($uid , $friendId , $groupid);

    public  function removeFriend($uid , $fiendId);

}