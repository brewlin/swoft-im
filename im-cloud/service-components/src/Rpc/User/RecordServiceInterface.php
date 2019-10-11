<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:29
 */

namespace ServiceComponents\Rpc\User;

/**
 * Class RecordServiceInterface
 * @package ServiceComponents\Rpc\User
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
    public function updateChatRecordIsRead($where,$data,$type);
    /**
     * 获取好友的聊天记录
     * @param $data
     */
    public function getFriendRecordById($uid , $data);
    /**
     * 获取群的聊天记录
     */
    public function getGroupRecordById($uid , $data);

    /**
     * 查看未读聊天记录
     * @param $uid
     * @return mixed
     */
    public function getAllNoReadRecord($uid);

    /**
     * 存储聊天记录
     * @param $data
     * @return mixed
     */
    public function newChatRecord($data);

    /**
     * 储存群组记录
     * @param $data
     * @return mixed
     */
    public function newGroupRecord($data);


}