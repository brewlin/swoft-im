<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 22:51
 */

namespace App\Exception\Http;

use ServiceComponents\Enum\StatusEnum;
class RegisterException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = '注册失败';

    public $data = [];

    public $statusCode = 2002;

}