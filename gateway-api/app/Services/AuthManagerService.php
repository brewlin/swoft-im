<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/11/26
 * Time: 20:43
 */

namespace App\Services;
use App\Lib\AdminNormalAccount;
use Swoft\Auth\AuthManager;
use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class AuthManagerService
 * @Bean()
 * @package App\Services
 */
class AuthManagerService extends AuthManager implements AuthManagerInterface
{
   /**
    * @var string
    */
   protected $cacheClass = Redis::class;
   /**
    * @var bool 开启缓存
    */
   protected $cacheEnable = true;

   public function adminBasicLogin(string $identity,string $credential):AuthSession
   {
       return $this->login(AdminNormalAccount::class,[
           'identity' => $identity,
           'credential' => $credential
       ]);
   }


}