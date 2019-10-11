<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 下午 17:03
 */

namespace App\Boot;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Task\Event\TaskEvent;

/**
 * Class TaskFinish
 * @Listener(TaskEvent::FINISH_TASK)
 * @package App\Boot
 */
class TaskFinish implements EventHandlerInterface
{
    public function handle(EventInterface $event)
    {
        //var_dump("3",$event->getParams());
    }

}