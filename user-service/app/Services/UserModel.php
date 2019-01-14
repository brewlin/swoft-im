<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 21:24
 */

namespace App\Services;


use App\Models\Entity\User;
use ServiceComponents\Rpc\User\UserModelInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserModel
 * @Service()
 */
class UserModel implements UserModelInterface
{
    public function getUser($where)
    {
        return User::findOne($where)->getResult();
    }
    public function newUser($data)
    {
        $user = new User();
        $result = $user->fill($data)->save()->getResult();
        return $result;
    }
    public function updateUser($id,$data)
    {
        return User::updateOne($data, ['id' => $id])->getResult();
    }
    public function getUserByNumbers($numbers)
    {
        $data = [];
        foreach ($numbers as $k => $v)
        {
            $data[] = User::findOne(['number' => $v])->getResult();
        }
        return $data;
    }
    public function getNumberById($id)
    {
        $user = User::findById($id)->getResult();
        return $user['number'];
    }
    public function getUserById($id)
    {
        return User::findById($id)->getResult();
    }
    public function getAllUser()
    {
        return User::findAll()->getResult();
    }
    public function searchUser($value , $page = null)
    {
            if(!$value)
                return User::findAll()->getResult();
            return User::query()
                ->orWhere('number','like','%'.$value.'%')
                ->orWhere('nickname','like','%'.$value.'%')
                ->orWhere('phone','like','%'.$value.'%')
                ->orWhere('email','like','%'.$value.'%')
                ->get()->getResult();
    }
}