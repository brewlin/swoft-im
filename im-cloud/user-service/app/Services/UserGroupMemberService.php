<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/15
 * Time: 6:31
 */

namespace App\Services;


use App\Models\Dao\UserGroupMemberDao;
use App\Models\Dao\UserModelDao;
use App\Models\Entity\User;
use App\Models\Service\MemberService;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Msg\MsgServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserGroupMemberService
 * @package App\Servicess
 * @Service()
 */
class UserGroupMemberService implements UserGroupMemberServiceInterface
{
    /**
     * @Inject()
     * @var MemberService
     */
    private $memberService;
    /**
     * @Inject()
     * @var UserGroupMemberDao
     */
    private $userGroupMemberDao;

    public function getFriends($arr)
    {
        return Message::success($this->memberService->getFriends($arr));
    }

    /**
     * @param $data
     * @param $currentUid
     * 添加好友
     */
    public function newFriends($data ,$currentUid)
    {
        $this->memberService->newFriends($data,$currentUid);
        return Message::success();
    }
    public function friendInfo($where)
    {
        return Message::success($this->memberService->friendInfo($where));
    }

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data)
    {
        $this->memberService->doReq($data);
        return Message::success();
    }

    /*
     * 检查二人是否是好友关系
     */
    public  function checkIsFriend($user1_id, $user2_id)
    {
        return Message::success($this->memberService->checkIsFriend($user1_id,$user2_id));
    }
    public function getAllFriends($id)
    {
        return Message::success($this->userGroupMemberDao->getAllFriends($id));
    }

    public function newFriend($uId, $friendId , $groupId )
    {
        return Message::success($this->userGroupMemberDao->newFriend($uId,$friendId,$groupId));
    }
    /**
     * 修改好友备注名
     */
    public function editFriendRemarkName($uid , $friendId , $remark)
    {
        return Message::success($this->userGroupMemberDao->editFriendRemarkName($uid,$friendId,$remark));
    }
    /**
     * 移动联系人
     * @param $uid 自己的id
     * @param $friendId 被移动的好友id
     * @param $groupid 移动的目标分组id
     */
    public function moveFriend($uid , $friendId , $groupid)
    {
        return Message::success($this->userGroupMemberDao->moveFriend($uid,$friendId,$groupid));
    }
    public  function removeFriend($uid , $fiendId)
    {
        return Message::success($this->userGroupMemberDao->removeFriend($uid,$fiendId));
    }
}