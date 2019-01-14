<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 13:34
 */

namespace App\Fallback;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Sg\Bean\Annotation\Fallback;

/**
 * Class RedisServiceFallback
 * @package App\Fallback
 * @Fallback("redisServiceFallback")
 */
class RedisServiceFallback implements UserCacheInterface {
	/*
	* 保存 token => userInfo
	*/
	public function saveTokenToUser($token, $user) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 保存 number => token
	*/
	public function saveNumToToken($number, $token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 根据number获取token
	*/
	public function getTokenByNum($number) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 根据 token 获得 number 信息
	*/
	public function getNumByToken($token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/**
	 * 根据token获取id信息
	 */
	public function getIdByToken($token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 保存 number => fd
	*/
	public function saveNumToFd($number, $fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 根据 number 获取 fd
	*/
	public function getFdByNum($number) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 根据 token 获取所有 user 信息
	*/
	public function getUserByToken($token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 保存好友请求的双方验证信息
	*/
	public function saveFriendReq($from_num, $to_num) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 获取好友验证
	*/
	public function getFriendReq($from_num) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	/*
		     * fd => token
	*/
	public function saveTokenByFd($fd, $token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	/*
		     * 获取fd => token
	*/
	public function getTokenByFd($fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function saveFds($fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	public function getFdFromSet() {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function setGroupFds($gnumber, $fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function getGroupFdsLen($gnumber) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function getGroupFd($gnumber, $index) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function delGroupFd($gnumber, $fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
	/*
		     * 销毁
	*/
	public function delTokenUser($token) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function delNumberUserOtherInfo($number) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function delFdToken($fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function delFriendReq($from_num) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}

	public function delFds($fd) {return "服务连接失败，请检查是否服务启动成功 或者调用失败";}
}