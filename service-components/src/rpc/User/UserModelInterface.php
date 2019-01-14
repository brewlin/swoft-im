<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 21:15
 */

namespace ServiceComponents\Rpc\User;


/**
 * Interface UserModelInterface
 * @package ServiceComponents\Rpc\User
 */
Interface UserModelInterface
{
    public function getUser($where);
    public function newUser($data);
    public function updateUser($id,$data);
    public function getUserByNumbers($numbers);
    public function getNumberById($id);
    public function getUserById($id);
    public function getAllUser();
    public function searchUser($value , $page = null);
}