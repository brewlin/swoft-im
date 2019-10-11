<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 上午 9:31
 */

namespace App\Models\Dao;


use App\Models\Entity\Group;
use App\Models\Entity\User;
use Swoft\Bean\Annotation\Bean;

/**
 * Class GroupModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class GroupModelDao
{

    public function getSum($where)
    {
        return Group::count('*',$where)->getResult();
    }

    public function getGroup($where, $single = false){
        if($single)
            return Group::findOne($where)->getResult();
        else
            return Group::findAll($where)->getResult();
    }
    public function getGroupOwnById($id,$key = null)
    {
        $res = Group::findOne(['id' => $id])->getResult();
        $res['user'] = User::findOne(['id' => $res['userId']])->getResult();
        if($key)
            return $res['user'][$key];
        return $res;
    }

    public function newGroup($data)
    {
        $id =  Group::query()->insert($data)->getResult();
        return $id;
    }
    public function getGroupOwner($id)
    {
        $res = Group::findById($id);
        $res['username'] = User::findOne(['number' => $res['user_number']])->getResult();
        return $res;
    }
    public function getNumberById($id)
    {
        $res = Group::findById($id)->getResult();
        return $res['gnumber'];
    }
    /**
     * 查找群
     */
    public function searchGroup($value ,$page = null)
    {
        if(!$value)
            return Group::findAll()->getResult();
        return Group::query()
            ->orWhere('ginfo','like','%'.$value.'%')
            ->orWhere('gname','like','%'.$value.'%')
            ->orWhere('gnumber','like','%'.$value.'%')
            ->orWhere('number','like','%'.$value.'%')
            ->get()->getResult();
    }

}