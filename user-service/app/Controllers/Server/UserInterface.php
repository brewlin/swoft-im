<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 下午 15:57
 */

namespace App\Controllers\Server;
use Swoft\Bean\Annotation\Bean;


/**
 * Interface UserInterface
 * @\Swoft\Bean\Annotation\Bean(ref="boy")
 * @package App\Controllers\Server
 */
interface UserInterface
{
   public function getData();

}