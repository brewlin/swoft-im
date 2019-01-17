<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 1:08
 */

namespace App\Services\Models;
use App\Models\Dao\UserRecordModelDao;
use ServiceComponents\Rpc\User\UserRecordModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserRecordModel
 * @package App\Services\Models
 * @Service()
 */
class UserRecordModel implements UserRecordModelInterface
{
    /**
     * @Inject()
     * @var UserRecordModelDao
     */
    private $userRecordModelDao;

    public function updateByWhere($where ,$data)
    {
        return $this->userRecordModelDao->updateByWhere($where,$data);
    }

    public function newRecord($data)
    {
        return $this->userRecordModelDao->newRecord($data);
    }
    public function getTimeStampAttr($value)
    {
        return $this->userRecordModelDao->getTimeStampAttr($value);
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    聊天对象的id
     * @return array
     */
    public function getAllChatRecordById($current , $toId)
    {
        return $this->userRecordModelDao->getAllChatRecordById($current,$toId);
    }
    /**
     * 查看未读聊天记录
     */
    public function  getAllNoReadRecord($uid)
    {
        return $this->userRecordModelDao->getAllNoReadRecord($uid);
    }
}