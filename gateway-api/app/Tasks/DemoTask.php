<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/11/28
 * Time: 20:46
 */

namespace App\Tasks;
use function Psy\debug;
use Swoft\Task\Bean\Annotation\Scheduled;
use Swoft\Task\Bean\Annotation\Task;


/**
 * Class DemoTask
 * @Task("demo")
 * @package App\Tasks
 */
class DemoTask
{
    /**
     * Scheduled(cron="* * * * * *")
     * @return string
     */
    public function cronTask()
    {
        file_put_contents("/tmp/1.txt","sdfsd",FILE_APPEND);
    }

    /**
     * Scheduled(cron="3-5 * * * * *")
     */
    public function cronooTask()
    {
       echo time();
    }

}
