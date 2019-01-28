<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 9:03
 */

namespace App\Services;


use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\LoginServiceInterface;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * @Service()
 * Class LoginService
 * @package App\Services
 */
class LoginService implements LoginServiceInterface
{
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCache;
    /*
     * 保存登陆后的初始信息
     * 存两个关联关系键值对
     * 1. token => userInfo
     * 2. uid => token
     */
    public function saveCache($token , $user)
    {
        $this->userCache->saveNumToToken($user['number'], $token);
        $this->userCache->saveTokenToUser($token, $user);
    }

}