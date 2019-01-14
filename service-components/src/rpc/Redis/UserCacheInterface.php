<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 10:14
 */

namespace ServiceComponents\Rpc\Redis;

use Swoft\Core\ResultInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Interface UserCacheInterface
 * @package ServiceComponents\Rpc\Redis
 * @method ResultInterface deferSaveTokenToUser($token, $user);
 * @method ResultInterface deferSaveNumToToken($number, $token);
 * @method ResultInterface deferGetTokenByNum($number);
 * @method ResultInterface deferGetNumByToken($token);
 * @method ResultInterface deferGetIdByToken($token);
 * @method ResultInterface deferSaveNumToFd($number, $fd);
 * @method ResultInterface deferGetFdByNum($number);
 * @method ResultInterface deferGetUserByToken($token);
 * @method ResultInterface deferSaveFriendReq($from_num, $to_num);
 * @method ResultInterface deferGetFriendReq($from_num);
 * @method ResultInterface deferSaveTokenByFd($fd, $token);
 * @method ResultInterface deferGetTokenByFd($fd);
 * @method ResultInterface deferSaveFds($fd);
 * @method ResultInterface deferGetFdFromSet();
 * @method ResultInterface deferSetGroupFds($gnumber,$fd);
 * @method ResultInterface deferGetGroupFdsLen($gnumber);
 * @method ResultInterface deferGetGroupFd($gnumber, $index);
 * @method ResultInterface deferDelGroupFd($gnumber, $fd);
 * @method ResultInterface deferDelTokenUser($token);
 * @method ResultInterface deferDelNumberUserOtherInfo($number);
 * @method ResultInterface deferDelFdToken($fd);
 * @method ResultInterface deferDelFriendReq($from_num);
 * @method ResultInterface deferDelFds($fd);
 */
interface UserCacheInterface
{
    /*
     * 保存 token => userInfo
     */
    public function saveTokenToUser($token, $user);
    /*
     * 保存 number => token
     */
    public   function saveNumToToken($number, $token);
    /*
     * 根据number获取token
     */
    public function getTokenByNum($number);
    /*
     * 根据 token 获得 number 信息
     */
    public  function getNumByToken($token);
    /**
     * 根据token获取id信息
     */
    public function getIdByToken($token);
    /*
     * 保存 number => fd
     */
    public  function saveNumToFd($number, $fd);
    /*
     * 根据 number 获取 fd
     */
    public  function getFdByNum($number);
    /*
     * 根据 token 获取所有 user 信息
     */
    public  function getUserByToken($token);
    /*
     * 保存好友请求的双方验证信息
     */
    public  function saveFriendReq($from_num, $to_num);
    /*
     * 获取好友验证
     */
    public  function getFriendReq($from_num);

    /*
     * fd => token
     */
    public  function saveTokenByFd($fd, $token);

    /*
     * 获取fd => token
     */
    public  function getTokenByFd($fd);

    public  function saveFds($fd);
    public  function getFdFromSet();

    public  function setGroupFds($gnumber,$fd);

    public  function getGroupFdsLen($gnumber);

    public  function getGroupFd($gnumber, $index);

    public  function delGroupFd($gnumber, $fd);
    /*
     * 销毁
     */
    public  function delTokenUser($token);

    public function delNumberUserOtherInfo($number);

    public  function delFdToken($fd);

    public  function delFriendReq($from_num);

    public function delFds($fd);
}