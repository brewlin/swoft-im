<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/15
 * Time: 22:59
 */

namespace App\Services\Models;
use App\Models\Dao\GroupMemberModelDao;
use App\Models\Entity\GroupMember;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class GroupMemberModel
 * @package App\Services\Models
 * @Service()
 */
class GroupMemberModel implements GroupMemberModelInterface
{

    /**
     * @Inject()
     * @var GroupMemberModelDao
     */
    private $groupMemberModelDao;
    public function newGroupMember($data)
    {
        return $this->groupMemberModelDao->newGroupMember($data);
    }
    public function getGroups($where)
    {
        return $this->groupMemberModelDao->getGroups($where);
    }
    public function getOneByWhere($where)
    {
        return $this->groupMemberModelDao->getOneByWhere($where);
    }
    public function getGroupNames($where)
    {
        return $this->groupMemberModelDao->getGroupNames($where);
    }
    public function getGroupMembers($gnumber)
    {
        return $this->groupMemberModelDao->getGroupMembers($gnumber);
    }
    /**
     * 删除群成员
     * @param $userNumber
     * @param $groupNumber
     */
    public function delMemberById($userNumber , $groupNumber)
    {
        return $this->groupMemberModelDao->delMemberById($userNumber,$groupNumber);
    }
    public function getNumberById($id)
    {
        return $this->groupMemberModelDao->getNumberById($id);
    }
    public function getIdByNumber($number)
    {
        return $this->groupMemberModelDao->getIdByNumber($number);
    }
}