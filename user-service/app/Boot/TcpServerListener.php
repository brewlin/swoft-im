<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 上午 11:51
 */

namespace App\Boot;
use Swoft\App;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bean\Annotation\SwooleListener;
use Swoft\Bootstrap\Listeners\Interfaces\CloseInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ConnectInterface;
use Swoft\Bootstrap\Listeners\Interfaces\ReceiveInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoole\Server;

/**
 * Class TcpServerListener
 * @SwooleListener({
 *     SwooleEvent::ON_RECEIVE,
 *     SwooleEvent::ON_CONNECT
 *     },
 *     type=SwooleEvent::TYPE_PORT
 *     )
 * @package App\Boot
 */
class TcpServerListener implements ReceiveInterface,ConnectInterface
{
    public function onReceive(Server $server, int $fd, int $reactorId, string $data)
    {
        var_dump($data);
        /** @var \Swoft\Rpc\Server\ServiceDispatcher $dispatcher */
        $dispatcher = App::getBean('ServiceDispatcher');
        $dispatcher->dispatch($server, $fd, $reactorId, $data);
        // TODO: Implement onReceive() method.
    }
    public function onConnect(Server $server, int $fd, int $reactorId)
    {
        var_dump("test--{$fd}\n");
        // TODO: Implement onConnect() method.
    }
}