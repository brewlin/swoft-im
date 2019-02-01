<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 12:45
 */

namespace App\Models\Dao;
use App\Models\Entity\GroupMember;
use App\Models\Entity\UserGroup;
use App\Models\Entity\UserGroupMember;
use Swoft\Bean\Annotation\Bean;
use Swoft\Db\Collection;
use Swoft\Db\DbDataResult;

/**
 * Class UserGroupModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class UserGroupModelDao
{
    public function list()
    {
        return $this->hasMany('GroupUserMember','groupid','id');
    }
    public function getAllFriends($id)
    {
        $list = UserGroup::findAll(['user_id' => $id])->getResult()->toArray();
        foreach ($list as $k => $v)//
        {
           $list[$k]['list'] = UserGroupMember::findAll(['user_group_id' => $v['id']])->getResult()->toArray();
        }
        return $list;
    }

    /**
     * 添加分组
     * @param $userId 用户id
     * @param $groupname 分组名
     */
    public function addGroup($userId , $groupname)
    {
        $data['user_id'] = $userId;
        $data['group_name'] = $groupname;
        $data['status'] = 1;
        return (new UserGroup())->fill($data)->save()->getResult();
    }

    /**
     * 修改分组名
     * @param $userId
     * @param $groupname
     */
    public function editGroup($id , $groupname)
    {
        return UserGroup::updateOne(['group_name' => $groupname],['id' => $id])->getResult();
    }
    public function updateByWhere($attr , $condition ,$single = true)
    {
        if($single)
            return UserGroup::updateOne($attr,$condition)->getResult();
        return UserGroup::updateAll($attr,$condition)->getResult();
    }
    /**
     * 删除分组名
     * 检查下面是否有好友
     * 将好友转移到默认分组去
     */
    public function delGroup($id ,  $user)
    {
        $group = UserGroup::findById($id)->getResult();
        if($group['user_id'] != $user['id'])
        {
            return false;
        }
        $default = $this->getDefaultGroupUser($user['id']);
        UserGroupMember::updateAll(['user_group_id' => $default['id']],['user_id' => $user['id'],'user_group_id' => $id])->getResult();
        return UserGroup::deleteById($id);
    }
    /**
     * 获取用户第一个分组信息
     */
    public function getDefaultGroupUser($userId)
    {
        return UserGroup::query()->where('user_id',$userId)->orderBy('id')->get()->getResult();
    }
}