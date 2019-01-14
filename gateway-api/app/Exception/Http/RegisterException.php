<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:51
 */

namespace App\Exception\Http;

use App\Enum\ExceptionEnum;
class RegisterException extends HttpExceptionHandler
{
    public $code = ExceptionEnum::FailCode;

    public $msg = '注册失败';

    public $data = [];

}