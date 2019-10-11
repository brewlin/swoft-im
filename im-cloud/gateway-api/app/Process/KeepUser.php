<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/6
 * Time: 下午12:14
 */

namespace App\Process;
use App\Models\Entity\User;
use App\Websocket\Service\UserService;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Rpc\Server\Bean\Annotation\Service;


/**
 * Class KeepUser
 * @package App\Process
 * 定时任务，统计哪些不在线的用户删除其缓存
 * @Bean()
 */
class KeepUser
{
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCacheService;
    public function run()
    {
            $serv = \Swoft::$server;
            $allUser = User::findAll(['status' => 1])->getResult();
            foreach ($allUser as $k => $v)
            {
               $fd = $this->userCacheService->getFdByNum($v['number']);
               if(!$serv->getServer()->getClientInfo($fd))
               {
                   $token  = $this->userCacheService->getTokenByNum($v['number']);
                   $user   = $this->userCacheService->getUserByToken($token);
                   App::getBean(UserService::class)->delUserToken(['user' => $user,'token' => $token]);
               }
            }
    }

}