<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:43
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class ParameterException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = '参数错误';

    public $data = [];

    public $statusCode = 2001;
}