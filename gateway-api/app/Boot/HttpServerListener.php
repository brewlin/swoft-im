<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 上午 11:42
 */

namespace App\Boot;
use App\Process\KeepUser;
use Psr\Http\Message\RequestInterface;
use Swoft\App;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\StartInterface;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoole\Server;
use Swoole\Timer;

/**
 * Class HttpServerListener
 * @package App\Boot
 * @ServerListener(event=SwooleEvent::ON_WORKER_START)
 */
class HttpServerListener implements WorkerStartInterface
{
    public function onWorkerStart(Server $server, int $workerId, bool $isWorkerr)
    {
//        if($workerId == 1)
//        {
//            $keepuser = App::getBean(KeepUser::class);
//            Timer::tick(20000, function () use ($keepuser) {
//                $keepuser->run();
//            });
//
//        }
    }
}