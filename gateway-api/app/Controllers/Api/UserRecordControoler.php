<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:54
 */

namespace App\Controllers\Api;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * Class UserRecordControoler
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im")
 */
class UserRecordControoler extends BaseController
{
    /**
     * @return mixed
     * @RequestMapping(route="record",method={RequestMethod::POST})
     * @Strings(from=ValidateFrom::POST,name="id")
     * @Strings(from=ValidateFrom::POST,name="type")
     * @Strings(from=ValidateFrom::POST,name="token")
     * @param Request $request
     */
    public function getChatRecordByToken($request)
    {
        $res = RecordServer::getAllChatRecordById($this->user['id'] , $this->request()->getRequestParam());
        return $this->success($res);

    }

    /**
     * 更新已读消息
     */
    public function updateIsReadChatRecord()
    {
        (new \App\Validate\ChatRecord('read'))->goCheck($this->request());
        $where = ['to_id' => $this->user['id'],'uid' => $this->request()->getParsedBody('uid'),'is_read' => 0];
        $data = ['is_read' => 1];
        $type = $this->request()->getParsedBody('type');
        RecordServer::updateChatRecordIsRead($where,$data,$type);
        return $this->success([],'收取消息成功');
    }
}