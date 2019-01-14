<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:43
 */

namespace App\Exception\Http;


use App\Enum\ExceptionEnum;

class ParameterException extends HttpExceptionHandler
{
    public $code = ExceptionEnum::FailCode;

    public $msg = '参数错误';

    public $data = [];
}