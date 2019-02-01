<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 上午 11:42
 */

namespace App\Boot;
use Psr\Http\Message\RequestInterface;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\StartInterface;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoole\Server;

/**
 * Class HttpServerListener
 * @package App\Boot
 * @ServerListener(event=SwooleEvent::ON_WORKER_START)
 */
class HttpServerListener implements WorkerStartInterface
{
    public function onWorkerStart(Server $server, int $workerId, bool $isWorkerr)
    {
        //echo $workerId."is started \n";
    }
}