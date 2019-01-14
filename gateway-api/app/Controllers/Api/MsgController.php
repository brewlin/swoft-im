<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:56
 */

namespace App\Controllers\Api;
use App\Middlewares\TokenCheckMiddleware;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * Class MsgController
 * @package App\Controllers\Api
 * @Controller(prefix="api/im/msg")
 * @Middleware(class=TokenCheckMiddleware::class)
 */
class MsgController
{
    /**
     * @RequestMapping(route="box/info")
     */
    public function getPersonalMsgBox()
    {
        return "sfs";

    }

}