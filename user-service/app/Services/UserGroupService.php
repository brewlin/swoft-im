<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29 0029
 * Time: 上午 10:34
 */

namespace App\Services;


use App\Models\Dao\RpcDao;
use App\Models\Dao\UserGroupModelDao;
use App\Models\Service\MemberService;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\User\UserGroupServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserGroupService
 * @package App\Services
 * @Service()
 */
class UserGroupService implements UserGroupServiceInterface
{
    /**
     * @Inject()
     * @var UserGroupModelDao
     */
    private $userGroupModelDao;
    /**
     * @Inject()
     * @var MemberService
     */
    private $memberService;
    /**
     * @Inject()
     * @var RpcDao
     */
    private $rpcDao;
    public function getUserGroupMember($userId)
    {
        $friends = $this->userGroupModelDao->getAllFriends($userId);
        $data = $this->memberService->getFriends($friends);
        return Message::success($data);
    }
    public function getAllFriends($userId)
    {
        return Message::success($this->userGroupModelDao->getAllFriends($userId));
    }
    public function addUserGroup($token, $groupname)
    {
        $id = $this->rpcDao->userCache->getIdByToken($token);
        $groupId = $this->userGroupModelDao->addGroup($id , $groupname);
        if($groupId)
            return Message::success($groupId);
        return Message::error();
    }
    public function updateByCondition($attr, $condition,$single = true)
    {
        $res = $this->userGroupModelDao->updateByWhere($attr,$condition,$single);
        return Message::success($res);
    }
    public function delGroup($id, $user)
    {
        return Message::success($this->userGroupModelDao->delGroup($id,$user));
    }

}