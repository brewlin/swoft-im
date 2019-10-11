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
    public function newGroupMember($data)
    {
        return GroupMember::query()->insert($data)->getResult();
    }

    public function getGroups($where)
    {
        $list = GroupMember::findAll($where)->getResult();
        foreach ($list as $k => $v)
        {
           $list[$k]['info'] = Group::findOne(['number' => $v['group_number']])->getResult();

        }
        return $list;
    }
    public function getOneByWhere($where)
    {
        return GroupMember::findOne($where);
    }
    public function getGroupNames($where)
    {
        $res = [];
        $list = GroupMember::findAll($where)->getResult();
        foreach ($list as $group)
        {
            $res[] = Group::findOne(['number' => $group['group_number']])->getResult();
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