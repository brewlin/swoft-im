<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 1:09
 */

namespace ServiceComponents\Rpc\User;

/**
 * Interface UserRecordModelInterface
 * @package ServiceComponents\Rpc\User
 */
interface UserRecordModelInterface 
{
    public function updateByWhere($where ,$data);
    public function newRecord($data);
    public function getTimeStampAttr($value);
    public function getAllChatRecordById($current , $toId);
    public function  getAllNoReadRecord($uid);
}