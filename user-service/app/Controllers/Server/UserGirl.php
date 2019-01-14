<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 下午 16:01
 */

namespace App\Controllers\Server;
use Swoft\Bean\Annotation\Bean;


/**
 * Class UserGirl
 * @Bean("girl")
 * @package App\Controllers\Server
 */
class UserGirl implements UserInterface
{
    public function getData()
    {
        return 'girl';
        // TODO: Implement getData() method.
    }

}