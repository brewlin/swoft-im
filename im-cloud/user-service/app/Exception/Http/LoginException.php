<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 17:19
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class LoginException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = '验证失败';

    public $data = [];

    public $statusCode = 2000;
}