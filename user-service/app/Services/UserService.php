<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/21
 * Time: 21:28
 */

namespace App\Services;
use App\Exception\Http\ParameterException;
use App\Models\Dao\UserModelDao;
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserService
 * @package App\Services
 * @Service()
 */
class UserService implements UserServiceInterface
{
    /**
     * @Inject()
     * @var UserModelDao
     */
    private $userModelDao;
    /**
     * 注册
     */
    public function register(string $email,string $nickname,string $password,string $repassword)
    {
        // 判断两次密码是否输入一致
        if (strcmp($password, $repassword))
            return Message::error(['msg' => '两次密码输入不一致']);

        // 查询用户是否已经存在
        $user = $this->userModelDao->getUser(['email' => $email]);
        if (!empty($user))
            return Message::error(['msg' => '该用户已存在']);

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