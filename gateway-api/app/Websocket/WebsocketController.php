<?php

namespace App\WebSocket;

use function PHPSTORM_META\type;
use ServiceComponents\Common\Message;
use Swoft\App;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\WebSocket\Server\Bean\Annotation\WebSocket;
use Swoft\WebSocket\Server\HandlerInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class EchoController
 * @package App\WebSocket
 * @WebSocket("/")
 */
class WebsocketController implements HandlerInterface
{
    /**
     * 在这里你可以验证握手的请求信息
     * - 必须返回含有两个元素的array
     *  - 第一个元素的值来决定是否进行握手
     *  - 第二个元素是response对象
     * - 可以在response设置一些自定义header,body等信息
     * @param Request $request
     * @param Response $response
     * @return array
     * [
     *  self::HANDSHAKE_OK,
     *  $response
     * ]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        return [self::HANDSHAKE_OK, $response];
    }

    /**
     * @param Server $server
     * @param Request $request
     * @param int $fd
     */
    public function onOpen(Server $server, Request $request, int $fd)
    {
       // $server->push($fd, 'hello, welcome! :)');
    }

    /**
     *  data = {
        "controller":"OnOpen",
        "action":"init",
        "content":{"token":token}
        };
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        try
        {
            $data = $frame->data;
            $data = json_decode($data,1);
            if(!$data)
                return $server->push($frame->fd,Message::sockData(['data' => '缺少数据']));
            //获取控制器
            $classname = 'App\\Websocket\\Controller\\'.$data['controller'];
            $action = $data['action'];
            if (class_exists($classname))
                    (new $classname($data['content'],$frame->fd))->$action();
        }catch(\Throwable $e)
        {
            $file      = $e->getFile();
            $line      = $e->getLine();
            $code      = $e->getCode();
            $exception = $e->getMessage();
            $data = [];
            $statusCode = 10000;
            if(property_exists($e,'data'))
                $data = $e->data;
            if(property_exists($e,'statusCode'))
                $statusCode = $e->statusCode;
            /**
             * 抓捕Rpc调用服务 返回的异常信息 swoft封装抛出
             * 调试使用
             * */
            if(method_exists($e,'getResponse'))
            {
                $reponse = $e->getResponse();
                $exception = $reponse['msg'];
                var_dump($reponse);
            }
            $data = ['code' => $code,'msg' => $exception,'data' => $data,'statusCode' => $statusCode, 'file' => $file, 'line' => $line];
            App::error(json_encode($data));
            $server->push($frame->fd,Message::sockData(['data' => $file.'--'.$line.'--'.$exception]));
        }
    }

    /**
     * on connection closed
     * @param Server $server
     * @param int $fd
     */
    public function onClose(Server $server, int $fd)
    {
        // you can do something. eg. record log, unbind user...
    }
}