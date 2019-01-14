<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 17:19
 */

namespace App\Exception\Http;


use App\Enum\ExceptionEnum;

class LoginException extends HttpExceptionHandler
{
    public $code = ExceptionEnum::FailCode;

    public $msg = '验证失败';

    public $data = [];
}