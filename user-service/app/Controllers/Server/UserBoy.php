<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 下午 15:58
 */

namespace App\Controllers\Server;
use Swoft\Bean\Annotation\Bean;


/**
 * Class UserBody
 * @Bean("boy")
 * @package App\Controllers\Server
 */
class UserBoy implements UserInterface
{
    public function getData()
    {
        return 'boy';
        // TODO: Implement getData() method.
    }

}