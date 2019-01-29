<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/14
 * Time: 22:13
 */

namespace ServiceComponents\Common;


use ServiceComponents\Enum\StatusEnum;

class Message
{
    public static function success($data = '', $msg = '', $code = StatusEnum::Success, $statusCode = StatusEnum::SuccessCode)
    {
        $data = [
            "code" => $code,
            "data" => $data,
            "msg" => $msg,
            "statusCode" => $statusCode
        ];
        return $data;
    }
    public static function error($data = '', $msg = '', $code = StatusEnum::Fail,$statusCode = StatusEnum::FailCode)
    {
        $data = [
            "code" => $code,
            "data" => $data,
            "msg" => $msg,
            "statusCode" => $statusCode
        ];
        return $data;
    }

}