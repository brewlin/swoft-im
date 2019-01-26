<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 9:02
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface LoginServiceInterface
 * @package ServiceComponents\Rpc\User
 */
interface LoginServiceInterface
{
    /*
     * 保存登陆后的初始信息
     * 存两个关联关系键值对
     * 1. token => userInfo
     * 2. uid => token
     */
    public function saveCache($token , $user);
}