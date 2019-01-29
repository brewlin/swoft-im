<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29 0029
 * Time: 上午 10:38
 */

namespace App\Models\Service;
use App\Models\Dao\MsgModelDao;
use App\Models\Dao\UserGroupMemberDao;
use App\Models\Dao\UserModelDao;
use App\Models\Entity\User;
use ServiceComponents\Rpc\Msg\MsgServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class MemberService
 * @package App\Models\Service
 * @Bean()
 */
class MemberService
{
    /**
     * @Reference("msgService")
     * @var MsgServiceInterface
     */
    private $msgService;
    /**
     * @Inject()
     * @var UserGroupMemberDao
     */
    private $userGroupMemberDao;
    /**
     * @Inject()
     * @var UserModelDao;
     */
    private $userModelDao;
    /**
     * @Reference("redisService")
     * @var UserCacheInterface
     */
    private $userCacheService;
    /**
     * @Inject()
     * @var FriendService
     */
    private $friendService;

    public function getFriends($arr)
    {
        foreach ($arr as &$group)
        {
            foreach ($group['list'] as $k => &$friend)
            {
                //检查是否有昵称存在 有则替换当前的昵称
                if(!empty($friend['remark_name']))
                {
                    $name = $friend['remark_name'];
                    $group['list'][$k] = $this->friendInfo(['id' => $friend['friend_id']]);
                    $group['list'][$k]['username'] = $name;
                }else
                {
                    $group['list'][$k] = $this->friendInfo(['id' => $friend['friend_id']]);
                }
            }
        }
        return $arr;
    }

    /**
     * @param $data
     * @param $currentUid
     * 添加好友
     */
    public function newFriends($data ,$currentUid)
    {
        //添加自己的好友
        $this->userGroupMemberDao->newFriend($currentUid ,$data['friend_id'] ,$data['group_user_id']);
        //请求方添加好友
        //获取消息里的数据
        $friend = $this->msgService->getDataById($data['msg_id']);
        $this->userGroupMemberDao->newFriend($friend['from'] , $friend['to'] ,$friend['group_user_id']);
    }
    public function friendInfo($where)
    {
        $user = User::findOne($where)->getResult();
        $user['status']  = $this->userCacheService->getTokenByNum($user['number'])?'online':'offline';   // 是否在线
        return $user;
    }

    // 处理接收或拒绝添加好友的通知操作
    public function doReq($data)
    {
        $from_number = $data['from_number'];
        $number      = $data['number'];
        $check       = $data['check'];

        $from_user = $this->friendService->friendInfo(['number'=>$from_number]);
        $user = $this->friendService->friendInfo(['number'=>$number]);


        if($from_user['online']){
            if($check){
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriend', $user))
                    ->getTaskData();
            }else{
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($from_number), 'newFriendFail', $number.'('.$user["nickname"].')'.' 拒绝好友申请'))
                    ->getTaskData();
            }
            $taskClass = new Task($taskData);
            TaskManager::async($taskClass);
        }

        if($check){
            if($user['online']){
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($number), 'newFriend', $from_user))
                    ->getTaskData();
                $taskClass = new Task($taskData);
                TaskManager::async($taskClass);
            }
        }
    }

    /*
     * 检查二人是否是好友关系
     */
    public  function checkIsFriend($user1_id, $user2_id)
    {
        $ids = FriendModel::getAllFriends($user1_id);
        if(in_array($user2_id, $ids)){
            return true;
        }
        return false;
    }

}