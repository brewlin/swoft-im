<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:56
 */

namespace App\Controllers\Api;
use App\Middlewares\TokenCheckMiddleware;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Msg\MsgServiceInterface;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class MsgController
 * @package App\Controllers\Api
 * @Controller(prefix="api/im/msg")
 * @Middleware(class=TokenCheckMiddleware::class)
 */
class MsgController extends BaseController
{
    /**
     * @Reference("msgService")
     * @var MsgServiceInterface
     */
    private $msgService;
    /**
     * @RequestMapping(route="box/info")
     */
    public function getPersonalMsgBox()
    {
        //返回form和to都为自己的信息
        $this->getCurrentUser();
        $res = ($this->msgService->getDataByUserId($this->user['id']))['data'];
        return Message::success($res,$this->user);
    }

}