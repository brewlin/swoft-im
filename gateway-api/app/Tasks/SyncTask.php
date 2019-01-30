<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Tasks;

use Swoft\Task\Bean\Annotation\Task;

/**
 * Sync task
 *
 * @Task("SyncTask")
 */
class SyncTask
{
    public function sendMsg($data)
    {
        $fd = $data['fd'];
        $res = $data['data'];
        return \Swoft::$server->push($fd,json_encode($res));
    }
    /**
     * 发送群组聊天
     * @param $data
     */
    public function sendGroupMsg($data)
    {
        $fd = $data['fd'];
        $res = $data['res'];
        $fds = $data['fds'];
        $serv = \Swoft::$server;
        foreach ($fds as $v)
            if($fd != $v)
                $serv->push($v, json_encode($res));
    }


    public function sendOfflineMsg($data)
    {
        $server = \Swoft::$server;
        $fd = $data['fd'];
        $sendData = $data['data'];
        $server->after(3000, function () use ($server,$fd,$sendData) {
            foreach ($sendData['data'] as $res)
            {
                $tmp = [];
                $tmp['type'] = $sendData['type'];
                $tmp['method'] = $sendData['method'];
                $tmp['data'] = $res;
                $server->push($fd,json_encode($tmp));
            }
        });
        return true;
    }
    /**
     * @param $data
     * 公用执行方法
     */
    public function doJob($data)
    {
        $model = new $data['class'];
        $method = $data['method'];
        $model->$method($data['data']);
    }
    public function saveMysql($data){
        $model = new $data['class'];
        $method = $data['method'];
        $model::$method($data['data']);
    }
    public function sendToALl($data){
        $serv = \Swoft::$server;
        $start_fd = 0;
        $myfd = $data['fd'];
        unset($data['fd']);
        while(true)
        {
            $conn_list = $serv->connection_list($start_fd, 10);
            if($conn_list===false or count($conn_list) === 0)
            {
                break;
            }
            $start_fd = end($conn_list);
            foreach($conn_list as $fd)
            {
                if($myfd!=$fd){
                    $status = $serv->connection_info($fd);
                    if($status['websocket_status']==3){
                        $serv->push($fd, json_encode($data));
                    }
                }
            }
        }
    }
}
