<?php
/**
 * Created by PhpStorm.
 * User: xiaodo
 * Date: 2019/1/19
 * Time: 下午5:11
 */

namespace App\WebsocketController;


use App\Exception\Http\RpcException;
use App\Exception\Http\SockException;
use App\Models\Dao\RpcDao;
use ServiceComponents\Enum\StatusEnum;
use Swoft\App;

class BaseWs
{
    protected $content;
    protected $fd;
    protected $rpcDao;
    public function __construct($content,$fd)
    {
        $this->content = $content;
        $this->fd = $fd;
        $this->rpcDao = App::getBean(RpcDao::class);
    }
    /*
    * 获取当前用户的信息
    */
    protected function getUserInfo()
    {
        $content = $this->content;
        $token = $content['token'];

        //调用rpc服务
        $this->rpcDao->userCache->getUserByToken($token);
        if(empty($user))
            return false;
        $data = [
            'token' => $content['token'],
            'fd'    => $this->fd,
            'user'  => $user
        ];
        return $data;
    }

    /*
     * 向用户发送格式化后的消息
     */
    protected function sendMsg($params =[]){
        $data = [
            'type'      => 'ws',
            'method'    => 'ok',
            'data'      => 'ok'
        ];
        if(array_key_exists('type',$params)){
            $data['type'] = $params['type'];
        }
        if(array_key_exists('method',$params)){
            $data['method'] = $params['method'];
        }
        if(array_key_exists('data',$params)){
            $data['data'] = $params['data'];
        }
        \Swoft::$server->sendTo($this->fd,json_encode($data));
    }

    /*
     * 通过 id 验证用户是否在线，以及是否存在
     */
    protected function onlineValidate($toId)
    {
        $ishas = $this->rpcDao->userService->getUserByCondition(['id' => $toId]);
        if($ishas['code'] != StatusEnum::Success)
            throw new RpcException();
        if(!$ishas)
            throw new SockException();
        $fd = $this->rpcDao->userCache->getFdByNum($ishas['number']);
        if(!$fd)
            throw new SockException(['msg' =>'用户不在线，已发送离线消息']);
        $user = [
            'fd'    => $fd,
            'user'  => $ishas
        ];
        return $user;
    }
}