<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 下午 16:30
 */

namespace App\Controllers\Server;


use Swoft\Proxy\Handler\HandlerInterface;

class TestHandler implements HandlerInterface
{
    private $target;
    public function __construct($target)
    {
        $this->target = $target;
    }
    public function invoke($method, $parameters)
    {
        // TODO: Implement invoke() method.
        $before = 'before';
        $result = $this->target->$method(...$parameters);
        $after = 'after';
        $result .= $before.$after;
        return $result;
    }

}