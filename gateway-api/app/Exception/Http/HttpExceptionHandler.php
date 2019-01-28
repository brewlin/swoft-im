<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/12
 * Time: 17:16
 */

namespace App\Exception\Http;


use ServiceComponents\Enum\StatusEnum;

class HttpExceptionHandler extends \Exception
{
    // 自定义数据
    public $data = [];

    //接口码
    public $statusCode = 1000;

    public function __construct($params = [])
    {
        // HTTP 状态码
        $this->code = StatusEnum::Fail;
        $this->message =  '未知错误';
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code', $params))
            $this->code = $params['code'];
        if(array_key_exists('msg', $params))
            $this->message = $params['msg'];
        if(array_key_exists('data', $params))
            $this->data = $params['data'];
        if(array_key_exists('statusCode', $params))
            $this->statusCode = $params['statusCode'];
    }
}
