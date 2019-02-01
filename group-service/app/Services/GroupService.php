<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29 0029
 * Time: 上午 11:02
 */

namespace App\Services;


use App\Exception\Http\GroupException;
use App\Exception\Http\RpcException;
use App\Models\Dao\GroupMemberModelDao;
use App\Models\Dao\GroupModelDao;
use App\Models\Dao\RpcDao;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\Group\GroupServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Db\Db;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class GroupService
 * @package App\Services
 * @Service()
 */
class GroupService implements GroupServiceInterface
{
    /**
     * @Inject()
     * @var GroupMemberModelDao
     */
    private $groupMemberModelDao;
    /**
     * @Inject()
     * @var GroupModelDao
     */
    private $groupModelDao;
    /**
     * @Inject()
     * @var RpcDao
     */
    private $rpcDao;
    public function getGroupListByNumber($userId)
    {
        return Message::success($this->groupMemberModelDao->getGroupNames(['user_id'=>$userId,'status' => 1]));
    }

    public function getGroupMembers($id)
    {
        $owner = $this->groupModelDao->getGroupOwner($id);
        //获取群成员
        $memberList = $this->groupMemberModelDao->getGroupMembers($owner['gnumber']);

        //调用用户服务  获取用户列表
        $userRes = $this->rpcDao->userService->getUserByNumbers($memberList);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $list = $userRes['data'];

        return Message::success(compact('owner','list'));
    }

    public function leaveGroup($id,$number)
    {
        $groupNumber = $this->groupMemberModelDao->getNumberById($id);
        $res = $this->groupMemberModelDao->delMemberById($number , $groupNumber);
        if($res)
            return Message::success('','成功');
        return Message::error('','失败');
    }
    public function getGroupByCondition($where)
    {
        $list = $this->groupModelDao->getGroup($where);
        return Message::success($list);
    }
    public function createGroup($data,$number ,$userNumber)
    {
        // 保存群信息，并加入群
        $group_data = [
            'gnumber'       => $number,
            'user_number'   => $userNumber,
            'ginfo'         => $data['des'],
            'gname'         => $data['des'],
            'groupname' => $data['groupName'],//群名称
            'approval' => $data['approval'],//验证方式 需要验证 不需要验证
            'number' => $data['number'],//群上限人数
        ];
        $member_data = [
            'gnumber'       => $number,
            'user_number'   => $userNumber,
        ];
        Db::beginTransaction();
        try
        {
            $id = $this->groupModelDao->newGroup($group_data);
            $this->groupMemberModelDao->newGroupMember($member_data);
            Db::commit();
        }catch (\Throwable $e)
        {
            Db::rollback();
            throw new GroupException();
        }
        return Message::success($id);
    }
    public function getGroup($where, $single = false)
    {
        return Message::success($this->groupModelDao->getGroup($where,$single));
    }
    public function getGroupMemberByCondition($where,$single = false)
    {
        if($single)
            return Message::success($this->groupMemberModelDao->getOneByWhere($where));
        return Message::success($this->groupMemberModelDao->getGroups($where));
    }
    public function searchGroup($value)
    {
        return Message::success($this->groupModelDao->searchGroup($value));
    }
    public function getGroupOwnById($id, $key = null)
    {
        return Message::success($this->groupModelDao->getGroupOwnById($id,$key));
    }
    public function newGroupMember($data)
    {
        return Message::success($this->groupMemberModelDao->newGroupMember($data));

    }
}