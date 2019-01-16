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
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\LoginServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
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
     * @var UserModelInterface
     */
    private $userModel;
    /**
     * @Reference("userService")
     * @var UserGroupModelInterface
     */
    private $userGroupModel;
    /**
     * @Reference("userService")
     * @var LoginServiceInterface
     */
    private $loginService;

    /**
     * @RequestMapping(route="/login")
     * 用户登录
     * 验证通过后，将信息存入 redis
     */
    public function login()
    {
        $email = request()->post('email');
        $password = request()->post()('password');

        // 查询用户是否已经存在
        $user = $this->userModel->getUser(['email' => $email]);
        if (empty($user))
            throw new LoginException(['msg' => '无效账号']);

        $userFd = $this->userCache->getFdByNum($user['number']);
        if ($userFd)
            \Swoft::$server->push($userFd, json_encode(['type' => 'ws', 'method' => 'belogin', 'data' => 'logout']));

        // 比较密码是否一致
        if (strcmp(md5($password), $user['password']))
            throw new LoginException(['msg' => '密码错误',]);

        // 更新登录时间
        $update = [ 'last_login' => time()];
        $this->userModel->updateUser($user['id'], $update);

        // 生成 token
        $token = Common::getRandChar(16);

        // 将用户信息存入缓存
        $this->loginService->saveCache($token,$user);

        // 返回 token
        return Message::sucess($token);
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

        // 查询用户是否已经存在
        $user = $this->userModel->getUser(['email' => $email]);
        if (!empty($user))
            throw new RegisterException(['msg' => '该用户已存在']);

        // 生成唯一number
        $number = Common::generate_code();
        while ($this->userModel->getUser(['number' => $number])) {
            $number = Common::generate_code();
        }

        // 入库
        $data = [
            'email' => $email,
            'password' => md5($password),
            'nickname' => $nickname,
            'number' => $number,
            'username' => $nickname
        ];
        $uid = $this->userModel->newUser($data);
        $res = $this->userGroupModel->addGroup($uid,"我的好友");
        if($res)
            return Message::sucess();
        return Message::error();

    }




}