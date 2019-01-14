<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20 0020
 * Time: 上午 11:48
 */

namespace App\Controllers\Api;

use App\Controllers\Server\ProxyTest;
use App\Controllers\Server\TestHandler;
use App\Controllers\Server\User;
use App\Controllers\Server\UserInterface;
use App\Controllers\Server\UserLogic;
use App\Middlewares\ActionTestMiddleware;
use App\Models\Entity\LeftTable;
use Swoft\Bean\Annotation\Inject;
use Swoft\Event\AppEvent;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Proxy\Proxy;
use Swoft\Task\Task;

/**
 * RESTful和参数验证测试demo.
 * @Middleware(class=ActionTestMiddleware::class)
 * @Controller(prefix="/test")
 */
class TestController
{
    /**
     * @Inject("userLogic")
     */
    private $userData;
    /**
     * teste控制器测试
     * @Middleware(class=ActionTestMiddleware::class)
     * @RequestMapping(route="test1", method={RequestMethod::GET})
     */
    public function test1()
    {
        $test = new LeftTable();
        return $test->getAll()->getResult();

    }
    /**
     * @RequestMapping(route="test2",method={RequestMethod::GET})
     */
    public function test2()
    {
        return $this->userData->getData();
    }
}