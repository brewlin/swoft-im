<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:31
 */

namespace App\Controllers\Api;
use App\Enum\ExceptionEnum;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
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
        $user = $this->userCache->getUserByToken($token);
        $this->user = $user;
        $this->user['fd'] = $this->userCache->getFdByNum($user['number']);
    }

    //公用返回方法
    public function success($data = null, $msg = null, $statusCode = ExceptionEnum::SuccessCode)
    {
        $data = [
            "code" => $statusCode,
            "data" => $data,
            "msg" => $msg
        ];
        return $data;
    }

    //公用返回方法
    public function error($data = null, $msg = null, $statusCode = ExceptionEnum::FailCode)
    {
        $data = [
            "code" => $statusCode,
            "data" => $data,
            "msg" => $msg
        ];
        return $data;
    }
}

