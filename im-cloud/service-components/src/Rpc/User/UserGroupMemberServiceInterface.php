<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/14
 * Time: 22:28
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserGroupMemeberServiceInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserGroupMemberServiceInterface
{
    public  function getFriends($arr);
    public  function newFriends($data ,$currentUid);
    public  function friendInfo($where);

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data);
    /*
     * 检查二人是否是好友关系
     */
    public function checkIsFriend($user1_id, $user2_id);
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