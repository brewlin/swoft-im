<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:35
 */

namespace App\Models\Dao;
use App\Models\Entity\Group;
use App\Models\Entity\GroupRecord;
use App\Models\Entity\User;
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
        GroupRecord::query()->insert($data)->getResult();
    }
    /**
     * @param $current 当前用户的id
     * @param $toId    群对象的id
     * @return array
     */
    public function getAllChatRecordById($uid , $id)
    {
        $recordList = GroupRecord::query()->where('uid',$uid)
                            ->where('gnumber',$id)
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
}