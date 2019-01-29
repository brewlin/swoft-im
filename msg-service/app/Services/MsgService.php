<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 19:13
 */

namespace App\Services;


use App\Models\Dao\MsgModelDao;
use App\Models\Entity\Msg;
use ServiceComponents\Rpc\Msg\MsgServiceInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class MsgModel
 * @package App\Services
 * @Service()
 */
class MsgService implements MsgServiceInterface
{
    /**
     * @Inject()
     * @var MsgModelDao
     */
    private $msgModelDao;
    /**
     * 根据用户id获取消息
     */
    public function getDataByUserId($userId)
    {
        return $this->msgModelDao->getDataByUserId($userId);
    }
    /**
     * 添加信息
     */
    public function addMsgBox($data)
    {
        return $this->msgModelDao->addMsgBox($data);
    }
    public function getDataById($id)
    {
        return $this->msgModelDao->getDataById($id);
    }
    public function updateById($id , $where)
    {
        return $this->msgModelDao->updateById($id,$where);
    }
    public function updateByWhere($where ,$update)
    {
        return $this->msgModelDao->updateByWhere($where,$update);
    }
    public function getOneByWhere($where)
    {
        return $this->msgModelDao->getOneByWhere($where);
    }
}