<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:30
 */

namespace App\Services;


use App\Models\Dao\GroupRecordModelDao;
use App\Models\Dao\RpcDao;
use App\Models\Dao\UserRecordModelDao;
use App\Models\Entity\Group;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\User\RecordServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class RecordService
 * @package App\Services
 * @Service()
 */
class RecordService implements RecordServiceInterface
{

    /**
     * @Inject()
     * @var UserRecordModelDao
     */
    private $userRecordModelDao;
    /**
     * @var GroupRecordModelDao;
     */
    private $groupRecordModelDao;
    /**
     * @Inject()
     * @var RpcDao
     */
    private $rpcDao;
    /**
     * 获取好友 或者群聊天记录
     * @param $data type id $uid
     */
    public function getAllChatRecordById($uid , $data)
    {
        if($data['type'] == 'friend')
        {
            return $this->getFriendRecordById($uid , $data);
        }else if($data['type'] == 'group')
        {
            return $this->getGroupRecordById($uid , $data);
        }else
        {
            return $this->getFriendRecordById($uid , $data);
        }
    }
    /**
     * 更新聊天记录的状态
     */
    public function updateChatRecordIsRead($where,$data,$type)
    {
        return $this->userRecordModelDao->updateByWhere($where,$data);
    }

    /**
     * 获取好友的聊天记录
     * @param $data
     */
    public function getFriendRecordById($uid , $data)
    {
        $list = $this->userRecordModelDao->getAllChatRecordById($uid , $data['id']);
        return $list;
    }
    /**
     * 获取群的聊天记录
     */
    public function getGroupRecordById($uid , $data)
    {
        $group = Group::findOne(['id' => $data['id']])->getResult();
        $groupNumber = $group['number'];
        $list = $this->groupRecordModelDao->getAllChatRecordById($uid , $groupNumber);
        return $list;
    }
    public function getAllNoReadRecord($uid)
    {
        return Message::success($this->userRecordModelDao->getAllNoReadRecord($uid));
    }
    public function newChatRecord($data)
    {
        $this->userRecordModelDao->newRecord($data);
    }
    public function newGroupRecord($data)
    {
        $this->groupRecordModelDao->newRecord($data);
    }

}