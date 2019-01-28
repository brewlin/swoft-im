<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 12:45
 */

namespace App\Models\Dao;
use App\Models\Entity\UserGroup;
use App\Models\Entity\UserGroupMember;
use Swoft\Bean\Annotation\Bean;

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
        UserGroupMember::findAll(['user_id' => $id]);
        $res = UserGroup::query()->leftJoin('user_group_member','user_group.id=user_group_member.user_group_id')
            ->where('user_group.user_id' , $id)->get(['user_group.*,user_group_member.remark_name'])->getResult();
        var_dump($res);
        /**
        $res = self::where('user_id',$id)
        ->with('list')
        ->select()->toArray();
         * **/
        return $res;
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
        var_dump($default);
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