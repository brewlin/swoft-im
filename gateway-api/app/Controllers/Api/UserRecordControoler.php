<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:54
 */

namespace App\Controllers\Api;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\User\UserRecordServiceInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserRecordControoler
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im")
 */
class UserRecordControoler extends BaseController
{
    /**
     * @Reference("useService")
     * @var UserRecordServiceInterface
     */
    private $userRecordService;
    /**
     * @return mixed
     * @RequestMapping(route="record",method={RequestMethod::GET})
     * @Strings(from=ValidateFrom::POST,name="id")
     * @Strings(from=ValidateFrom::POST,name="type")
     * @Strings(from=ValidateFrom::POST,name="token")
     * @param Request $request
     */
    public function getChatRecordByToken($request)
    {
        $this->getCurrentUser();
        $res = $this->userRecordService->getAllChatRecordById($this->user['id'] , $request->query());
        return Message::sucess($res);
    }

    /**
     * 更新已读消息
     * @RequestMapping(route="chat/record/read",method={RequestMethod::POST})
     * @Strings(from=ValidateFrom::POST,name="uid")
     * @Strings(from=ValidateFrom::POST,name="type")
     * @Strings(from=ValidateFrom::POST,name="token")
     * @param Request $request
     */
    public function updateIsReadChatRecord($request)
    {
        $this->getCurrentUser();
        $where = ['to_id' => $this->user['id'],'uid' => $request->post('uid'),'is_read' => 0];
        $data = ['is_read' => 1];
        $type = $request->post('type');
        $this->userRecordService->updateChatRecordIsRead($where,$data,$type);
        return Message::sucess([],'收取消息成功');
    }
}