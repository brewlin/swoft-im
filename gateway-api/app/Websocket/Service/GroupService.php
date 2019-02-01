<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 20189/1/04
 * Time: 下午11:34
 */

namespace App\Websocket\Service;



use App\Models\Dao\RpcDao;
use App\WebSocket\Common\TaskHelper;
use Swoft\App;
use Swoft\Task\Task;

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
        $taskData = TaskHelper::getTaskData('newGroup',$data,$user['fd']);
        Task::deliver('SyncTask','sendMsg',$taskData,Task::TYPE_ASYNC);
    }

    /**
     * 处理加群申请
     * @param $data
     */
    public function doReq($fromNumber , $check ,$data)
    {

        $from_user = (App::getBean(RpcDao::class)->userService('getUserByCondition',['number' => $fromNumber],true))['data'];

        if($from_user['online']){
            if($check)
            {
                $taskData = TaskHelper::getTaskData('newGroup',$data,App::getBean(RpcDao::class)->userCache->getFdByNum($fromNumber));
                Task::deliver('SyncTask','sendMsg',$taskData,Task::TYPE_ASYNC);
            }else
            {
                $taskData = TaskHelper::getTaskData('newGroupFailMsg','newGroupFailMsg', '加群审核未通过',App::getBean(RpcDao::class)->userCache('getFdByNum',$fromNumber));
                Task::deliver('SyncTask','sendMsg',$taskData,Task::TYPE_ASYNC);
            }
        }
    }
}