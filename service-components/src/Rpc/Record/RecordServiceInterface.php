<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 0:56
 */

namespace ServiceComponents\Rpc\Record;

/**
 * Interface RecordServiceInterface
 * @package ServiceComponents\Rpc\Record
 */
interface RecordServiceInterface
{
    /**
     * 获取好友 或者群聊天记录
     * @param $data type id $uid
     */
    public function getAllChatRecordById($uid , $data);
    /**
     * 更新聊天记录的状态
     */
    public function updateChatRecordIsRead($where,$data);

    /**
     * 获取好友的聊天记录
     * @param $data
     */
    public function getFriendRecordById($uid , $data);
    /**
     * 获取群的聊天记录
     */
    public function getGroupRecordById($uid , $data);
}