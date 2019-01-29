<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 下午 12:45
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class FileException extends HttpExceptionHandler
{
    public $code = StatusEnum::Fail;

    public $msg = '文件异常';

    public $data = [];

    public $statusCode = 2004;
}