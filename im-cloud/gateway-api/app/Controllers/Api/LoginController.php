<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:50
 */

namespace App\Controllers\Api;
use App\Exception\Http\LoginException;
use App\Exception\Http\ParameterException;
use App\Exception\Http\RegisterException;
use App\Services\UserService;
use Composer\XdebugHandler\Status;
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\LoginServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class LoginController
 * @Controller("/")
 */
class LoginController extends BaseController
{
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCache;
    /**
     * @Reference("userService")
     * @var UserServiceInterface
     */
    private $loginService;

    /**
     * 用户登录
     * 验证通过后，将信息存入 redis
     * @RequestMapping(route="/login")
     * @Strings(from=ValidatorFrom::POST,name="email")
     * @Strings(from=ValidatorFrom::POST,name="password")
     */
    public function login()
    {
        $email = request()->post('email');
        $password = request()->post('password');
        $logRes = $this->loginService->login($email,$password);
        if($logRes['code'] == StatusEnum::Success)
        {
            $user = $logRes['data']['user'];
            $userFd = $this->userCache->getFdByNum($user['number']);
            if ($userFd)
                \Swoft::$server->push($userFd, json_encode(['type' => 'ws', 'method' => 'belogin', 'data' => 'logout']));
            return Message::success($logRes['data']['token'],$logRes['msg']);
        }
        return Message::error($logRes['data']['token'],$logRes['msg']);
    }
    /**
     * @RequestMapping(route="/register")
     * @Strings(from=ValidatorFrom::POST,name="email")
     * @Strings(from=ValidatorFrom::POST,name="password")
     * @Strings(from=ValidatorFrom::POST,name="nickname")
     * @Strings(from=ValidatorFrom::POST,name="repassword")
     * 用户注册
     */
    public function register()
    {
        // 验证
        $email = request()->post('email');
        $nickname = request()->post('nickname');
        $password = request()->post('password');
        $repassword = request()->post('repassword');

        // 判断两次密码是否输入一致
        if (strcmp($password, $repassword))
            throw new ParameterException(['msg' => '两次密码输入不一致']);
        $regRes = $this->loginService->register($email,$nickname,$password);
        if($regRes['code'] == StatusEnum::Success)
            return Message::success('',$regRes['msg']);
        return Message::error('',$regRes['msg']);

    }




}