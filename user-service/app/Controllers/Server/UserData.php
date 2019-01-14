<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 下午 16:09
 */

namespace App\Controllers\Server;
use Swoft\Bean\Annotation\Bean;


/**
 * Class UserData
 * @Bean("userData")
 * @package App\Controllers\Server
 */
class UserData
{
    public function getData()
    {
        return "data";
    }

}