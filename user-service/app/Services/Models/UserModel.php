<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 21:24
 */

namespace App\Services\Models;


use App\Models\Dao\UserModelDao;
use ServiceComponents\Rpc\User\UserModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserModel
 * @Service()
 */
class UserModel implements UserModelInterface
{
    /**
     * @Inject()
     * @var UserModelDao
     */
    private $userModelDao;
    public function getUser($where)
    {
        return $this->userModelDao->getUser($where);
    }
    public function newUser($data)
    {
        return $this->userModelDao->newUser($data);
    }
    public function updateUser($id,$data)
    {
        return $this->userModelDao->updateUser($id,$data);
    }
    public function getUserByNumbers($numbers)
    {
        return $this->userModelDao->getUserByNumbers($numbers);
    }
    public function getNumberById($id)
    {
        return $this->userModelDao->getNumberById($id);
    }
    public function getUserById($id)
    {
        return $this->userModelDao->getUserByNumbers($id);
    }
    public function getAllUser()
    {
        return $this->userModelDao->getAllUser();
    }
    public function searchUser($value , $page = null)
    {
        return $this->userModelDao->searchUser($value,$page);
    }
}