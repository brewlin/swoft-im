<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29 0029
 * Time: 上午 10:33
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserGroupServiceInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserGroupServiceInterface
{
    /**
     * @获取分组好友
     * @param $userId
     * @return mixed
     */
    public function getUserGroupMember($userId);

    /**
     * 获取所有好友
     * @param $userId
     * @return mixed
     */
    public function getAllFriends($userId);

    /**
     * 添加用户分组
     * @param $token
     * @param $groupname
     * @return mixed
     */
    public function addUserGroup($token,$groupname);

    /**
     * 根据条件更新
     * @param $attr
     * @param $condition
     * @return mixed
     */
    public function updateByCondition($attr , $condition,$single = true);

    /**
     * 删除分组
     * @param $id
     * @param $user
     * @return mixed
     */
    public function delGroup($id,$user);

}