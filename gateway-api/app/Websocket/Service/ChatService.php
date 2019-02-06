<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2018/9/13
 * Time: 下午13:10
 */
namespace App\Websocket\Service;
use App\Exception\Http\SockException;
use App\Models\Dao\RpcDao;
use App\Services\RecordService;
use App\Tasks\SyncTask;
use App\WebSocket\Common\TaskHelper;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Task\Task;

/**
 * Class ChatService
 * @package App\Websocket\Service
 * @Bean()
 */
class ChatService
{
    /*
     *  发送聊天消息
     *  异步，做标记是自己的还是对方发的
     */
    public function sendPersonalMsg($data)
    {
        $toData = [
            'username' => $data['from']['user']['username'],
            'avatar' => $data['from']['user']['avatar'],
            'id' => $data['from']['user']['id'],
            'type' => 'friend',//聊天类型，好友聊天
            'mine'  => false,                       // true自己的消息 ，false对方的消息
            'fromid' => $data['from']['user']['id'],
            'content'  => $data['data'],
            'timestamp' => time()*1000,
            'number'=> $data['from']['user']['number'],  // 哪来的
        ];
        //异步任务
        $data = TaskHelper::getTaskData('chat',$toData,$data['to']['fd']);
        Task::deliver("SyncTask",'sendMsg',[$data],Task::TYPE_ASYNC);
    }
    /**
     * 发送离线消息
     * @param $data
     */
    public function sendOfflineMsg($fd ,$sendData)
    {
        $fromData = [];
        foreach ($sendData as $data)
        {
            $toData = [
                'username' => $data['from']['user']['username'],
                'avatar' => $data['from']['user']['avatar'],
                'id' => $data['from']['user']['id'],
                'type' => 'friend',//聊天类型，好友聊天
                'mine'  => false,                       // true自己的消息 ，false对方的消息
                'fromid' => $data['from']['user']['id'],
                'content'  => $data['data'],
                'timestamp' => time()*1000,
                'number'=> $data['from']['user']['number'],  // 哪来的
            ];
            $fromData[] = $toData;
        }
        $data = TaskHelper::getTaskData('sendOfflineMsg',$fromData,$fd);
        Task::deliver('SyncTask','sendOfflineMsg',[$data],Task::TYPE_ASYNC);
    }
    /*
     * 存储消息记录
     */
    public function savePersonalMsg($data)
    {
        $taskData = [
                'service'    => 'recordService',
                'method'   => 'newChatRecord',
                'data'     => [
                    'user_id'       => $data['from']['user']['id'],
                    'friend_id'     => $data['to']['user']['id'],
                    'content'      => $data['data'],
                    'is_read' => $data['is_read']
                ]
        ];
        Task::deliver('SyncTask','saveMysql',[$taskData],Task::TYPE_ASYNC);
    }
    /*
     * 发送群组聊天记录
     * {
            username: "纸飞机" //消息来源用户名
            ,avatar: "http://tp1.sinaimg.cn/1571889140/180/40030060651/1" //消息来源用户头像
            ,id: "100000" //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,type: "friend" //聊天窗口来源类型，从发送消息传递的to里面获取
            ,content: "嗨，你好！本消息系离线消息。" //消息内容
            ,cid: 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
            ,mine: false //是否我发送的消息，如果为true，则会显示在右方
            ,fromid: "100000" //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
            ,timestamp: 1467475443306 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
        }
     */
    public function sendGroupMsg($data)
    {
        $rpcDao = App::getBean(RpcDao::class);
        $groupRes = $rpcDao->groupService('getGroup',['gnumber' => $data['gnumber']],true);
        if($groupRes != StatusEnum::Success)
            throw new SockException();
        $group = $groupRes['data'];
        $user = $data['user']['user'];
        $res = [
            'method'    => 'groupChat',
            'type'      => 'ws',
            'data'      => [
                'username' => $user['nickname'],
                'avatar' => $user['avatar'],
                'id' => $group['id'],
                'type' => 'group',
                'content' => $data['data'],
                'mine' => false,
                'fromid' => $user['id'],
                'timestamp' => time()*1000,
            ]
        ];
        $myfd = $data['user']['fd'];

        $groupRes = $rpcDao->groupService('getGroupMembers',$data['gnumber']);
        if($groupRes != StatusEnum::Success)
            throw new SockException();
       $groupMembers = $groupRes['data'];
        //待发送的fds
        $friendFds = [];
        foreach ($groupMembers as $v)
            $friendFds[] = $rpcDao->userCache('getFdByNum',$v);
        //投递异步任务
        $taskData = [
                'fd' => $myfd,
                'res' => $res,
                'fds' => $friendFds
            ];
        Task::deliver('SyncTask','sendGroupMsg',[$taskData],Task::TYPE_ASYNC);
    }

    // 存储群组消息
    public function saveGroupMsg($data)
    {
        $taskData = [
                'class'    => 'recordService',
                'method'   => 'newGroupRecord',
                'data'     => [
                    'user_id'       => $data['user']['user']['id'],
                    'group_number'   => $data['gnumber'],
                    'content'      => $data['data']
                ]
        ];
        Task::deliver('SyncTask','saveMysql',[$taskData],Task::TYPE_ASYNC);
    }

}