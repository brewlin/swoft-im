<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 19:49
 */

namespace App\Services\Models;


use App\Models\Dao\UserGroupMemberDao;
use ServiceComponents\Rpc\User\UserGroupMemberModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserGroupMemberModel
 * @package App\Services\Models
 * @Service()
 */
class UserGroupMemberModel implements UserGroupMemberModelInterface
{
    /**
     * @Inject()
     * @var UserGroupMemberDao
     */
    private $userGroupMemberDao;
    public function getAllFriends($id)
    {
        return $this->userGroupMemberDao->getAllFriends($id);
    }

    public function newFriend($uId, $friendId , $groupId )
    {
        $this->userGroupMemberDao->newFriend($uId,$friendId,$groupId);
    }
    /**
     * 修改好友备注名
     */
    public function editFriendRemarkName($uid , $friendId , $remark)
    {
        return $this->userGroupMemberDao->editFriendRemarkName($uid,$friendId,$remark);
    }
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public function moveFriend($uid , $friendId , $groupid)
    {
        return $this->userGroupMemberDao->moveFriend($uid,$friendId,$groupid);
    }
    public  function removeFriend($uid , $fiendId)
    {
        return $this->userGroupMemberDao->removeFriend($uid,$fiendId);
    }

}