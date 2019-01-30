<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30 0030
 * Time: 下午 15:20
 */

namespace App\WebSocket\Common;


class TaskHelper
{
    public static function getTaskData($method,$data,$fd)
    {
        $taskData = [
            'fd' => $fd,
            'data' =>[

                    'type'      => 'ws',
                    'method'    => $method,
                    'data'      => $data
            ]
        ];
        return $taskData;
    }
}