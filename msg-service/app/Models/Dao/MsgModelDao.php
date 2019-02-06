<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 19:10
 */

namespace App\Models\Dao;
use App\Models\Entity\Msg;
use App\Models\Entity\User;
use Swoft\Bean\Annotation\Bean;

/**
 * Class MsgModelDao
 * @package App\Models\Dao
 * @Bean()
 */
class MsgModelDao
{
    /**
     * 根据用户id获取消息
     */
    public function getDataByUserId($userId)
    {
        $msg = Msg::query()->orWhere('from',$userId)
            ->orWhere('to',$userId)
            ->orderBy('send_time','desc')
            ->get()
            ->getResult()->toArray();
        foreach ($msg as $k => $v)
        {
            $msg[$k]['to'] = User::findOne(['id' => $v['to']])->getResult();
            $msg[$k]['from'] = User::findOne(['id' => $v['from']])->getResult();
        }
        return $msg;
    }
    /**
     * 添加信息
     */
    public function addMsgBox($data)
    {
        return (new Msg())->fill($data)->save()->getResult();
    }
    public function getDataById($id)
    {
        return Msg::findById($id)->getResult();
    }
    public function updateById($id , $where)
    {
        return Msg::updateOne($where,['id' => $id])->getResult();
    }
    public function updateByWhere($where ,$update)
    {
        return Msg::updateOne($update,$where)->getResult();
    }
    public function getOneByWhere($where)
    {
        return Msg::findAll($where);
    }
}