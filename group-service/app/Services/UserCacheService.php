<?php
/***
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 上午10:13
 */

namespace App\Services;

use ServiceComponents\Rpc\Redis\UserCacheInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Core\ResultInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class UserCacheService
 * @package App\Services
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
 * @Service()
 */
class UserCacheService implements UserCacheInterface {
    /**
     * @Inject("redisCache")
     * @var \Swoft\Redis\Redis
     */
    private $redisCache;

	/**
	 *  保存 token => userInfo
	 */
	public function saveTokenToUser($token, $user) {
		if (!is_array($user)) {
			$user = json_decode(json_encode($user), true);
		}
		$user = [
			'id' => $user['id'],
			'number' => $user['number'],
			'nickname' => $user['nickname'],
			'username' => $user['username'],
			'sex' => $user['sex'],
			'avatar' => $user['avatar'],
			'sign' => $user['sign'],
			'last_login' => $user['last_login'],
		];
		$key = \config('cache.cacheName.token_user');
		$key = sprintf($key, $token);
		return $this->redisCache->hMset($key, $user);
	}

	/**
	 * 保存 number => token
	 */
	public function saveNumToToken($number, $token) {
		$key = \config('cache.cacheName.number_userOtherInfo');
		$key = sprintf($key, $number);
		return $this->redisCache->hSet($key, 'token', $token);
	}

	/**
	 * 根据number获取token
	 */
	public function getTokenByNum($number) {
		$key = \config('cache.cacheName.number_userOtherInfo');
		$key = sprintf($key, $number);
		return $this->redisCache->hGet($key, 'token');
	}

	/**
	 * 根据 token 获得 number 信息
	 */
	public function getNumByToken($token) {
		$key = \config('cache.cacheName.token_user');
		$key = sprintf($key, $token);
		return $this->redisCache->hGet($key, 'number');
	}
	/**
	 * 根据token获取id信息
	 */
	public function getIdByToken($token) {
		$res = self::getUserByToken($token);
		return $res['id'];
	}
	/**
	 *
	 * 保存 number => fd
	 */
	public function saveNumToFd($number, $fd) {
		$key = \config('cache.cacheName.number_userOtherInfo');
		$key = sprintf($key, $number);
		return $this->redisCache->hSet($key, 'fd', $fd);
	}

	/**
	 * 根据 number 获取 fd
	 */
	public function getFdByNum($number) {
		$key = \config('cache.cacheName.number_userOtherInfo');
		$key = sprintf($key, $number);

		return $this->redisCache->hGet($key, 'fd');
	}

	/**
	 * 根据 token 获取所有 user 信息
	 */
	public function getUserByToken($token) {
		$key = \config('cache.cacheName.token_user');
		$key = sprintf($key, $token);

		return $this->redisCache->hGetAll($key);
	}

	/**
	 * 保存好友请求的双方验证信息
	 */
	public function saveFriendReq($from_num, $to_num) {
		$key = \config('cache.cacheName.friend_req');
		$key = sprintf($key, $from_num);

		return $this->redisCache->set($key, $to_num);
	}

	/**
	 * 获取好友验证
	 */
	public function getFriendReq($from_num) {
		$key = \config('cache.cacheName.friend_req');
		$key = sprintf($key, $from_num);

		return $this->redisCache->get($key);
	}

	/**
	 * fd => token
	 */
	public function saveTokenByFd($fd, $token) {
		$key = \config('cache.cacheName.fd_token');
		$key = sprintf($key, $fd);

		return $this->redisCache->set($key, $token);
	}

	/**
	 * 获取fd => token
	 */
	public function getTokenByFd($fd) {
		$key = \config('cache.cacheName.fd_token');
		$key = sprintf($key, $fd);

		return $this->redisCache->get($key);
	}

	public function saveFds($fd) {
		$key = \config('cache.cacheName.all_fd');

		return $this->redisCache->sAdd($key, $fd);
	}
	public function getFdFromSet() {
		$key = \config('cache.cacheName.all_fd');

		return $this->redisCache->sRandMember($key);
	}

	public function setGroupFds($gnumber, $fd) {
		$key = \config('cache.cacheName.group_number_fd');
		$key = sprintf($key, $gnumber);

		return $this->redisCache->lPush($key, $fd);
	}

	public function getGroupFdsLen($gnumber) {
		$key = \config('cache.cacheName.group_number_fd');
		$key = sprintf($key, $gnumber);

		return $this->redisCache->lLen($key);
	}

	public function getGroupFd($gnumber, $index) {
		$key = \config('cache.cacheName.group_number_fd');
		$key = sprintf($key, $gnumber);
		return $this->redisCache->lIndex($key, $index);
	}

	public function delGroupFd($gnumber, $fd) {
		$key = \config('cache.cacheName.group_number_fd');
		$key = sprintf($key, $gnumber);
		return $this->redisCache->lRem($key, $fd,0);
	}
	/**
	 * 销毁
	 */
	public function delTokenUser($token) {
		$key = \config('cache.cacheName.token_user');
		$key = sprintf($key, $token);
		self::delHashKey($key);
	}

	public function delNumberUserOtherInfo($number) {
		$key = \config('cache.cacheName.number_userOtherInfo');
		$key = sprintf($key, $number);
		self::delHashKey($key);
	}

	public function delFdToken($fd) {
		$key = \config('cache.cacheName.fd_token');
		$key = sprintf($key, $fd);

		return $this->redisCache->del($key);
	}

	public function delFriendReq($from_num) {
		$key = \config('cache.cacheName.friend_req');
		$key = sprintf($key, $from_num);

		return $this->redisCache->del($key);
	}

	public function delFds($fd) {
		$key = \config('cache.cacheName.all_fd');

		return $this->redisCache->sRem($key, $fd);
	}

	/**
	 * 删除 hash 键下的所有值
	 */
	private function delHashKey($key) {

		$res = $this->redisCache->hKeys($key);
		if ($res) {
			foreach ($res as $val) {
				$this->redisCache->hDel($key, $val);
			}
		}

	}
}