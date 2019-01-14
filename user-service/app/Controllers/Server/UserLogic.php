<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 15:36
 */

namespace App\Controllers\Server;


use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;

/**
 * Class UserLogic
 * @Bean("userLogic")
 * @package App\Controllers\Server
 */
class UserLogic
{
    /**
     * @Inject("userData")
     */
    private $user;
    public function getData()
    {
        return array_merge($this->user->getData(),['logic']);
    }

}