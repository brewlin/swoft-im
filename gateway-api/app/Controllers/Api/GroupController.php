<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:38
 */

namespace App\Controllers\Api;
use App\Middlewares\TokenCheckMiddleware;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * Class Group
 * @package App\Controllers\Api
 * @Controller(prefix="api/im/group")
 * @Middleware(class=TokenCheckMiddleware::class)
 */
class GroupController extends BaseController
{
    /**
     * @RequestMapping(route="user/add")
     * @return string
     *
     */
    public function getGroup()
    {

        $this->test();
    }

}