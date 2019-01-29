<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29 0029
 * Time: 上午 11:06
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class RpcException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = 'Rpc数据请求失败';

    public $data = [];

    public $statusCode = 4001;
}