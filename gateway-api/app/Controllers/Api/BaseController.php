<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:31
 */

namespace App\Controllers\Api;
use App\Enum\StatusEnum;
use App\Models\Dao\RpcDao;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class Base
 * @Controller("/")
 */
class BaseController
{
    public $user = null;

    /**
     * @Reference("redisCache")
     * @var UserCacheInterface
     */
    private $userCache;

    public function getCurrentUser()
    {
        //从请求上下文获取 全局 request() response()
        $headerToken = request()->getHeaderLine('token');
        $requestToken = request()->input('token');
        $token = $headerToken ? $headerToken : $requestToken;
        $rpcDao = App::getBean(RpcDao::class);
        $user = $rpcDao->userCache('getUserByToken',$token);
        $this->user = $user;
        $this->user['fd'] = $rpcDao->userCache('getFdByNum',$user['number']);
    }
}

