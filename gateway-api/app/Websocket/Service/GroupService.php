<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/18
 * Time: 下午8:09
 */

namespace App\Websocket\Service;


use App\Exception\Websocket\WsException;
use App\Model\Group as GroupModel;
use App\Model\GroupMember as GroupMemberModel;
use App\Task\Task;
use App\Task\TaskHelper;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\Task\TaskManager;

class GroupService
{
    public function sendNewGroupInfo($g_info, $user){
        // 异步通知
        $data  = [
            'id'            => $g_info['gid'],
            'avatar'         => '/timg.jpg',
            'groupname'     => $g_info['gname'],
            'type'          => 'group'

        ];
        $taskData = (new TaskHelper('sendMsg', $user['fd'], 'newGroup', $data))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

    /**
     * 处理加群申请
     * @param $data
     */
    public function doReq($fromNumber , $check ,$data)
    {

        $from_user = FriendService::friendInfo(['number'=>$fromNumber]);

        if($from_user['online']){
            if($check){
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($fromNumber), 'newGroup', $data))
                    ->getTaskData();
            }else{
                $taskData = (new TaskHelper('sendMsg', UserCacheService::getFdByNum($fromNumber), 'newGroupFailMsg', '加群审核未通过'))
                    ->getTaskData();
            }
            $taskClass = new Task($taskData);
            TaskManager::async($taskClass);
        }
    }
}