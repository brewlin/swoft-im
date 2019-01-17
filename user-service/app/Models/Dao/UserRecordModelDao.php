<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/18
 * Time: 1:12
 */

namespace App\Models\Dao;
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
        $model = new self();
        return $model->where(function($query)use($current,$toId){
            $query->where('uid',$current)->where('to_id',$toId);
        })
            ->whereOr(function($query)use($current , $toId){
                $query->where('uid',$toId)->where('to_id',$current);
            })
            ->with('username')
            ->with('avatar')
            ->select(function($query){
                $query->field(['uid' => 'id','created_time'=>'timestamp','data'=>'content']);
            });
    }
    /**
     * 查看未读聊天记录
     */
    public function  getAllNoReadRecord($uid)
    {
        $model = new self();
        return $model->where(['to_id' => $uid,'is_read' => 0])
            ->with('user')
            ->with('touser')
            ->select();
    }
}