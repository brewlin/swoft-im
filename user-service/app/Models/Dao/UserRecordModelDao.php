<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 1:12
 */

namespace App\Models\Dao;
use App\Models\Entity\User;
use App\Models\Entity\UserRecord;
use Swoft\Bean\Annotation\Bean;

/**
 * Class UserRecordModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class UserRecordModelDao
{
    public function user(){
        return $this->belongsTo('User','uid','id');
    }

    public function touser(){
        return $this->belongsTo('User','to_id','id');
    }
    public function updateByWhere($where ,$data)
    {
        return UserRecord::updateAll($data,$where);
    }

    public function newRecord($data)
    {
        return UserRecord::query()->insert($data)->getResult();
    }
    public function username()
    {
        return $this->belongsTo('User','id')->bind('username');
    }
    public function avatar()
    {
        return $this->belongsTo('User','id')->bind('avatar');
    }
    public function getTimeStampAttr($value)
    {
        return strtotime($value)*1000;
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    聊天对象的id
     * @return array
     */
    public function getAllChatRecordById($current , $toId)
    {
        $recordList1 = UserRecord::query()->where('user_id' ,$current)->where('friend_id',$toId)->get()->getResult()->toArray();
        $recordList2 = UserRecord::query()->where('user_id' ,$toId)->where('friend_id',$current)->get()->getResult()->toArray();
        $recordList = array_merge($recordList1,$recordList2);
        foreach ($recordList as $k => $v)
        {
            unset($recordList1[$k]['id']);
            $recordList1[$k]['id'] = $v['userId'];
            $recordList1[$k]['timestamp'] = $v['createTime'];
            $recordList1[$k]['content'] = $v['data'];
            $user = User::findOne(['id' => $v['userId']])->getResult();
            $recordList[$k]['username'] = $user['username'];
            $recordList[$k]['avatar'] = $user['avatar'];
        }
        return $recordList;
    }
    /**
     * 查看未读聊天记录
     */
    public function  getAllNoReadRecord($uid)
    {
        $list = UserRecord::query()
            ->where('user_id',$uid)
            ->where('is_read',0)
            ->get()
            ->getResult();
        foreach ($list as $k => $v)
        {
            $user = User::findOne(['id' => $v['userId']])->getResult();
            $touser = User::findById($v['friendId'])->getResult();
            $list[$k]['user'] = $user;
            $list[$k]['touser'] = $touser;
        }
        return $list;
    }
}