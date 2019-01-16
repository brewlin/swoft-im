<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 上午 9:31
 */

namespace App\Services\Models;


use App\Models\Dao\GroupModelDao;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use Swoft\Bean\Annotation\Inject;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Class GroupModel
 * @package App\Services\Models
 * @Service()
 */
class GroupModel implements GroupModelInterface
{
    /**
     * @Inject()
     * @var GroupModelDao
     */
    private $groupModelDao;
    public function getSum($where)
    {
        return $this->groupModelDao->getSum($where);

    }
    public function getGroup($where, $single = false)
    {
        return $this->groupModelDao->getGroup($where,$single);
    }
    public function getGroupOwnById($id,$key = null)
    {
        return $this->groupModelDao->getGroupOwnById($id,$key);
    }
    public function newGroup($data)
    {
        return $this->groupModelDao->newGroup($data);
    }
    public function getGroupOwner($id)
    {
        return $this->groupModelDao->getGroupOwner($id);
    }
    public function getNumberById($id)
    {
        return $this->groupModelDao->getNumberById($id);
    }
    /**
     * 查找群
     */
    public function searchGroup($value ,$page = null)
    {
        return $this->groupModelDao->searchGroup($value,$page);
    }
}