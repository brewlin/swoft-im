<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 21:54
 */

namespace App\Models\Service;
use App\Models\Entity\User;
use ServiceComponents\Rpc\Msg\MsgModelInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Task\Task;

/**
 * Class FriendService
 * @package App\Models\Service
 * @Bean()
 */
class FriendService
{
    /**
     * @Reference("msgService")
     * @var MsgModelInterface
     */
    private $msgModel;
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCacheService;
    public function getFriends($arr)
    {
        $res = [];
        foreach ($arr as $val)
            $res[] = $this->friendInfo(['id'=>$val]);
        return $res;
    }

    public function friendInfo($where)
    {
        $user = User::findOne($where)->getResult();
        $data['id'] = $user['id'];
        $data['avatar'] = $user['avatar'];
        $data['number'] = $user['number'];
        $data['nickname'] = $user['nickname'];
        $data['sign'] = $user['sign'];
        $data['last_login'] = $user['last_login'];
        $data['online']  = $this->userCacheService->getFdByNum($user['number'])?1:0;   // 是否在线
        return $data;
    }

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data)
    {
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];

        $from_user = $this->friendInfo(['number'=>$from_number]);
        $user = $this->friendInfo(['number'=>$number]);
        //获取好友请求方的分组
        $msg = $this->msgModel->getDataById($data['msg_id']);
        $user['groupid'] = $msg['group_user_id'];//好友所在分组

        if($from_user['online'])
        {
            if($check)
            {
                $result  = Task::deliver('sync', 'mysql', [], Task::TYPE_CO);
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriend', $user))
                    ->getTaskData();
            }else{
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriendFail', $number.'('.$user["nickname"].')'.' 拒绝好友申请'))
                    ->getTaskData();
            }
            $taskClass = new Task($taskData);
            TaskManager::async($taskClass);
        }

//        if($check){
//            if($user['online']){
//                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($number), 'newFriend', $from_user))
//                    ->getTaskData();
//                $taskClass = new Task($taskData);
//                TaskManager::async($taskClass);
//            }
//        }
    }

    /*
     * 检查二人是否是好友关系
     */
    public function checkIsFriend($user1_id, $user2_id){
//        $ids = FriendModel::getAllFriends($user1_id);
        $ids = GroupUserMember::getAllFriends($user1_id);
        if(in_array($user2_id, $ids)){
            return true;
        }
        return false;
    }

}