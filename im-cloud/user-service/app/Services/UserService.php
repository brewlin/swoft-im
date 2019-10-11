<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/21
 * Time: 21:28
 */

namespace App\Services;
use App\Exception\Http\GroupException;
use App\Exception\Http\LoginException;
use App\Exception\Http\ParameterException;
use App\Exception\Http\RegisterException;
use App\Models\Dao\RpcDao;
use App\Models\Dao\UserGroupModelDao;
use App\Models\Dao\UserModelDao;
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Db\Db;
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
     * @Inject()
     * @var UserGroupModelDao
     */
    private $userGroupModelDao;
    /**
     * @Inject()
     * @var RpcDao
     */
    private $rpcDao;
    public function register($email, $nickname, $password)
    {
        // 查询用户是否已经存在
        $user = $this->userModelDao->getUser(['email' => $email]);
        if (!empty($user))
            throw new RegisterException(['msg' => '该用户已存在']);

        // 生成唯一number
        $number = Common::generate_code();
        while ($this->userModelDao->getUser(['number' => $number]))
            $number = Common::generate_code();

        // 入库
        $data = [
            'email' => $email,
            'password' => md5($password),
            'nickname' => $nickname,
            'number' => $number,
            'username' => $nickname
        ];
        Db::beginTransaction();
        try
        {
            $uid = $this->userModelDao->newUser($data);
            $this->userGroupModelDao->addGroup($uid,"我的好友");
            Db::commit();
        }catch (\Throwable $e)
        {
            Db::rollback();
            return Message::error('','注册失败');
        }
        return Message::success('','注册成功');
    }

    /**
     * 登录
     * @param $email
     * @param $password
     */
    public function login($email, $password)
    {
        // 查询用户是否已经存在
        $user = $this->userModelDao->getUser(['email' => $email],true);
        if (empty($user))
            throw new LoginException(['msg' => '无效账号']);

        // 比较密码是否一致
        if (strcmp(md5($password), $user['password']))
            throw new LoginException(['msg' => '密码错误',]);

        // 更新登录时间
        $update = [ 'last_login' => time()];
        $this->userModelDao->updateUser($user['id'], $update);

        // 生成 token
        $token = Common::getRandChar(16);
        // 将用户信息存入缓存
        $this->rpcDao->userCache('saveNumToToken',$user['number'], $token);
        $this->rpcDao->userCache('saveTokenToUser',$token,$user);
        return Message::success(['token' => $token,'user' => $user],'登录成功');
    }
    public function getUserByNumbers($memberList)
    {
        return Message::success($this->userModelDao->getUserByNumbers($memberList));

    }
    public function getInformation($id, $type)
    {
        if($type == 'friend')
        {
            $info = $this->userModelDao->getUser(['id' => $id]);
            $info['type'] = 'friend';
        }else if($type == 'group')
        {
            //调用群组服务
            $groupRes = $this->rpcDao->groupService->getGroup(['id' => $id] , true);
            if($groupRes['code'] != StatusEnum::Fail)
                throw new GroupException();
            $info = $groupRes['data'];
            $info['type'] = 'group';
        }else
        {
            return Message::error('','类型错误');
        }
        return Message::success($info);
    }
    public function getUserByCondition($where,$single = false)
    {
        return Message::success($this->userModelDao->getUser($where,$single));
    }
    public function updateUserByCondition($attr, $condition)
    {
        return Message::success($this->userModelDao->updateByWhere($attr,$condition));
    }
    public function searchUser($value)
    {
        return Message::success($this->userModelDao->searchUser($value));
    }
    public function getAllUser()
    {
        return Message::success($this->userModelDao->getAllUser());
    }

}