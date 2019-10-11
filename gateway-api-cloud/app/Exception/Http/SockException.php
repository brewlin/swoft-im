<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30 0030
 * Time: 上午 10:38
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class SockException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = 'websocket处理 是啊比';

    public $data = [];

    public $statusCode = 5001;
}