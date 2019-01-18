<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18 0018
 * Time: 下午 12:43
 */

namespace App\Models\Dao;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class RpcDao
 * @package App\Models\Dao
 * @Bean()
 * @property GroupModelInterface $groupModel
 */
class RpcDao
{
    /**
     * @Reference("groupService")
     * @var GroupModelInterface
     */
    private $groupModel;

}