<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/15
 * Time: 23:07
 */

namespace App\Models\Dao;
use App\Models\Entity\Group;
use App\Models\Entity\GroupMember;
use Swoft\Bean\Annotation\Bean;

/**
 * Class GroupMemberModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class GroupMemberModelDao
{
    protected $hidden = ['id','creater_time'];

    public function info()
    {
        return $this->belongsTo('Group','gnumber','gnumber');
    }

    public function newGroupMember($data)
    {
        return GroupMember::query()->insert($data)->getResult();
    }

    public function getGroups($where)
    {
        return GroupMember::findAll($where)->getResult();
//        return self::where($where)->with('info')->select();
    }
    public function getOneByWhere($where)
    {
        return GroupMember::findOne($where);
    }
    public function getGroupNames($where)
    {
        $res = [];
        $list = self::where($where)->with('info')->select();
        foreach ($list as $group)
        {
            $res[] = $group['info'];
        }
        return $res;

    }
    public function getGroupMembers($gnumber)
    {
        return GroupMember::findAll(['gnumber' => $gnumber],['field' => ['user_number']])->getResult();
    }

    /**
     * 删除群成员
     * @param $userNumber
     * @param $groupNumber
     */
    public function delMemberById($userNumber , $groupNumber)
    {
        return GroupMember::query()->where('user_number',$userNumber)
                                    ->where('gnumber',$groupNumber)
                                    ->delete()
                                    ->getResult();
    }
    public function getNumberById($id)
    {
        $res =  GroupMember::findById($id);
        return $res['gnumber'];
    }
    public function getIdByNumber($number)
    {
        $res =  GroupMember::findOne(['gnumber' => $number])->getResult();
        return $res['id'];
    }

}