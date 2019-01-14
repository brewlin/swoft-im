<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:57
 */

namespace App\Services;


use App\Models\Entity\UserGroup;
use App\Models\Entity\UserGroupMember;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserGroupModel
 * @package App\Services
 * @Service()
 */
class UserGroupModel implements UserGroupModelInterface
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
        $data['groupname'] = $groupname;
        $data['status'] = 1;
        return self::create($data);
    }

    /**
     * 修改分组名
     * @param $userId
     * @param $groupname
     */
    public function editGroup($id , $groupname)
    {
        return self::update(['groupname' => $groupname],['id' => $id]);
    }
    /**
     * 删除分组名
     * 检查下面是否有好友
     * 将好友转移到默认分组去
     */
    public function delGroup($id ,  $user)
    {
        $group = self::get($id);
        if($group['user_id'] != $user['id'])
        {
            return false;
        }
        $default = self::getDefaultGroupUser($user['id']);
        (new GroupUserMember())->where('user_id',$user['id'])
            ->where('groupid' , $id)
            ->update(['groupid' => $default['id']]);
        return self::destroy($id);
    }
    /**
     * 获取用户第一个分组信息
     */
    public function getDefaultGroupUser($userId)
    {
        return self::where('user_id' , $userId)->order('id','asc')->find();
    }
}