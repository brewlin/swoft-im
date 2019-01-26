<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:56
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserGroupModelInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserGroupModelInterface
{
    public function getAllFriends($id);

    /**
     * 添加分组
     */
    public function addGroup($userId , $groupname);

    /**
     * 修改分组名
     */
    public function editGroup($id , $groupname);
    /**
     * 删除分组名
     * 检查下面是否有好友
     * 将好友转移到默认分组去
     */
    public function delGroup($id ,  $user);
    /**
     * 获取用户第一个分组信息
     */
    public function getDefaultGroupUser($userId);
}