<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 17:19
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class GroupException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = '群操作失败';

    public $data = [];

    public $statusCode = 4000;
}