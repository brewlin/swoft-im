<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/16
 * Time: 下午9:20
 */
namespace App\Websocket\Service;
class ChatService
{
//    /*
//     *  发送聊天消息
//     *  异步，做标记是自己的还是对方发的
//     */
//    public function sendPersonalMsg($data){
//        // 给自己发
//        $myData = [
//            'time'  => date("H:i:s", time()),
//            'flag'  => 1,                       // 1自己的消息 ，2对方的消息
//            'data'  => $data['data'],
//            'number'=> $data['to']['user']['number']    // 跟谁聊
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['from']['fd'], 'chat', $myData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);
//
//        // 给对方发
//        $toData = [
//            'time'  => date("H:i:s", time()),
//            'flag'  => 2,                       // 1自己的消息 ，2对方的消息
//            'data'  => $data['data'],
//            'number'=> $data['from']['user']['number']  // 哪来的
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['to']['fd'], 'chat', $toData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);
//    }
    /*
     *  发送聊天消息
     *  异步，做标记是自己的还是对方发的
     */
    public function sendPersonalMsg($data){
//        // 给自己发
//        $myData = [
//            'username' => $data['to']['user']['username'],
//            'avatar' => $data['to']['user']['avatar'],
//            'id' => $data['to']['user']['id'],
//            'type' => 'friend',//聊天类型，好友聊天
//            'mine'  => true,                       // true自己的消息 ，false对方的消息
//            'fromid' => $data['to']['user']['id'],
//            'content'  => $data['data'],
//            'timestamp' => time()*1000,
//            'number'=> $data['to']['user']['number']    // 跟谁聊
//        ];
//        $taskData = (new TaskHelper('sendMsg', $data['from']['fd'], 'chat', $myData))
//            ->getTaskData();
//        $taskClass = new Task($taskData);
//        TaskManager::async($taskClass);
        // 给对方发
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
        $taskData = (new TaskHelper('sendMsg', $data['to']['fd'], 'chat', $toData))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
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
        $taskData = (new TaskHelper('sendOfflineMsg', $fd, 'chat', $fromData))
            ->getTaskData();
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }
    /*
     * 存储消息记录
     */
    public function savePersonalMsg($data){
        $taskData = [
            'method' => 'saveMysql',
            'data'  => [
                'class'    => 'App\Model\ChatRecord',
                'method'   => 'newRecord',
                'data'     => [
                    'uid'       => $data['from']['user']['id'],
                    'to_id'     => $data['to']['user']['id'],
                    'data'      => $data['data'],
                    'is_read' => $data['is_read']
                ]
            ]
        ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
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
    public function sendGroupMsg($data){
        $group = Group::getGroup(['gnumber' => $data['gnumber']],true);
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
        $groupMembers  = GroupMember::getGroupMembers($data['gnumber']);
        //待发送的fds
        $friendFds = [];
        foreach ($groupMembers as $v)
            $friendFds[] = UserCacheService::getFdByNum($v);
        //投递异步任务
        $taskData = [
            'method' => 'sendGroupMsg',
            'data'  => [
                'fd' => $myfd,
                'res' => $res,
                'fds' => $friendFds
                ]
            ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

    // 存储群组消息
    public function saveGroupMsg($data){
        $taskData = [
            'method' => 'saveMysql',
            'data'  => [
                'class'    => 'App\Model\GroupChatRecord',
                'method'   => 'newRecord',
                'data'     => [
                    'uid'       => $data['user']['user']['id'],
                    'gnumber'   => $data['gnumber'],
                    'data'      => $data['data']
                ]
            ]
        ];
        $taskClass = new Task($taskData);
        TaskManager::async($taskClass);
    }

}