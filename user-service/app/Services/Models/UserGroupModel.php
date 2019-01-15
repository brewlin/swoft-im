<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:57
 */

namespace App\Services\Models;


use App\Models\Dao\UserGroupModelDao;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserGroupModel
 * @package App\Services
 * @Service()
 */
class UserGroupModel implements UserGroupModelInterface
{
    /**
     * @Inject()
     * @var UserGroupModelDao
     */
    private $userGroupModelDao;
    public function getAllFriends($id)
    {
        return $this->userGroupModelDao->getAllFriends($id);
    }
    /**
     * 添加分组
     * @param $userId 用户id
     * @param $groupname 分组名
     */
    public function addGroup($userId , $groupname)
    {
        return $this->userGroupModelDao->addGroup($userId,$groupname);
    }

    /**
     * 修改分组名
     * @param $userId
     * @param $groupname
     */
    public function editGroup($id , $groupname)
    {
        return $this->userGroupModelDao->editGroup($id, $groupname);
    }
    /**
     * 删除分组名
     * 检查下面是否有好友
     * 将好友转移到默认分组去
     */
    public function delGroup($id ,  $user)
    {
        return $this->userGroupModelDao->delGroup($id, $user);
    }
    /**
     * 获取用户第一个分组信息
     */
    public function getDefaultGroupUser($userId)
    {
        return $this->userGroupModelDao->getDefaultGroupUser($userId);
    }
}