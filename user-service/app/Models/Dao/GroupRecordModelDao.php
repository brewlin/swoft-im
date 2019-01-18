<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:35
 */

namespace App\Models\Dao;
use Swoft\Bean\Annotation\Bean;

/**
 * Class GroupRecordModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class GroupRecordModelDao
{
    public function newRecord($data)
    {
        $model = new self();
        foreach ($data as $key=>$value){
            $model->$key = $value;
        }
        $model->save();
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    群对象的id
     * @return array
     */
    public function getAllChatRecordById($uid , $id)
    {
        $model = new self();
        return $model->where('uid' , $uid)->where('gnumber' ,$id)
            ->with('username')
            ->with('avatar')
            ->select(function($query){
                $query->field(['uid' => 'id','created_time'=>'timestamp','data'=>'content']);
            });
    }
}