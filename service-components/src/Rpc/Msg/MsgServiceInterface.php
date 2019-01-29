<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 19:11
 */

namespace ServiceComponents\Rpc\Msg;

/**
 * Interface MsgModelInterface
 * @package ServiceComponents\Rpc\Msg
 */
interface MsgServiceInterface
{
    /**
     * 根据用户id获取消息
     */
    public function getDataByUserId($userId);
    /**
     * 添加信息
     */
    public function addMsgBox($data);
    public function getDataById($id);
    public function updateById($id , $where);
    public function updateByWhere($where ,$update);
    public function getOneByWhere($where);
}