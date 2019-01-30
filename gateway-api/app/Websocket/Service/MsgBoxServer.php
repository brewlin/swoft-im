<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/8/18
 * Time: 16:11
 */

namespace App\Websocket\Service;



class MsgBoxServer
{
    /**
     * @param $data
     * $param $uid 当前用户id
     * 当好友请求时更新状态
     */
    public function updateStatus($data , $uId)
    {
        //判断如果没有msg_id 则是通过websocket推送添加的好友，则需要查询消息id
        $msgbox = new MsgBox();
        $msgbox::updateById($data['msg_id'] , ['type' => $data['msg_type'] ,'status' => $data['status'] ,'read_time' => time()]);

    }
}
