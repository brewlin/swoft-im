<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 上午 9:31
 */

namespace App\Models\Dao;


class GroupModelDao
{

    public function getSum($where){
        return self::where($where)->count();
    }

    public function getGroup($where, $single = false){
        if($single){
            return self::where($where)->find();
        }else{
            return self::where($where)->select();
        }

    }
    public function user()
    {
        return $this->belongsTo('User','user_number','number');
    }
    public function getGroupOwnById($id,$key = null)
    {
        $res = self::with('user')->where('id',$id)->find();
        if($key)
            return $res['user'][$key];
        return $res;
    }

    public function newGroup($data){
        $model = new self();
        $model->save($data);
        return $model->id;
    }
    public function username()
    {
        return $this->belongsTo('User','user_number','number')->bind('username');
    }
    public function getGroupOwner($id)
    {
        return self::with('username')->find($id);
    }
    public function getNumberById($id)
    {
        $res = self::get($id);
        return $res['gnumber'];
    }
    /**
     * 查找群
     */
    public function searchGroup($value ,$page = null)
    {
        if($page == null)
        {
            if(empty($value))
                return self::select();
            return self::whereOr('ginfo','like','%'.$value.'%')
                ->whereOr('gname','like','%'.$value.'%')
                ->whereOr('gnumber','like','%'.$value.'%')
                ->whereOr('number','like','%'.$value.'%')
                ->select();
        }
        if(empty($value))
            return self::page($page)->select();
        return self::whereOr('ginfo','like','%'.$value.'%')
            ->whereOr('gname','like','%'.$value.'%')
            ->whereOr('gnumber','like','%'.$value.'%')
            ->whereOr('number','like','%'.$value.'%')
            ->limit(16)->page($page)->select();
    }

}