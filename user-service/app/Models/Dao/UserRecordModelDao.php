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
        $recordList = UserRecord::query()
            ->andWhere('uid',$current)
            ->where('to_id',$toId)
            ->closeWhere()
            ->orWhere('uid',$toId)
            ->where('to_id',$current)
            ->closeWhere()
            ->andWhere('group_number',$id)
            ->get(["uid as id","created_time as timestamp","data as content"])
            ->getResult();
        foreach ($recordList as $k => $v)
        {
            $user = User::findOne(['number' => $v['userNumber']])->getResult();
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
            ->where('to_id',$uid)
            ->where('is_read',0)
            ->get()
            ->getResult();
        foreach ($list as $k => $v)
        {
            $user = User::findOne(['id' => $v['to']])->getResult();
            $touser = User::findById($v['from'])->getResult();
            $list[$k]['user'] = $user;
            $list[$k]['touser'] = $touser;
        }
        return $list;
    }
}