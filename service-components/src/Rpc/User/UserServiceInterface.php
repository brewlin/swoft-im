<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/21
 * Time: 21:28
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserServiceInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserServiceInterface
{
    /**
     * 注册
     */
    public function register(string $email,string $nickname,string $password,string $repassword);

}