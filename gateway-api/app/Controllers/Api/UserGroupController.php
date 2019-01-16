<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:54
 */

namespace App\Controllers\Api;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserGroupController
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im/group")
 */
class UserGroupController extends BaseController
{
    /**
     * @Reference("userService")
     * @var UserCacheInterface
     */
    private $userCacheService;
    /**
     * @Reference("userService")
     * @var UserGroupModelInterface
     */
    private $userGroupModel;
    /**
     * 分组名添加
     * @RequestMapping(route="user/add",method={RequestMethod::GET})
     * @Strings(from=ValidateFrom::GET,name="token")
     * @Strings(from=ValidateFrom::GET,name="groupname")
     * @param Request $request
     */
    public function addMyGroup($request)
    {
        $token = $request->query('token');
        $groupname = $request->query('groupname');
        $id = $this->userCacheService->getIdByToken($token);
        $groupId = $this->userGroupModel->addGroup($id , $groupname);
        return Message::sucess(['id' => $groupId , 'groupname' => $groupname]);
    }
    /**
     * 分组名修改
     * @RequestMapping(route="user/edit",method={RequestMethod::GET})
     * @Strings(from=ValidateFrom::GET,name="id")
     * @Strings(from=ValidateFrom::GET,name="groupname")
     * @param Request $request
     */
    public function editMyGroup($request)
    {
        $data = $request->query();
        $res = $this->userGroupModel->editGroup($data['id'],$data['groupname']);
        if(!$res)
            return Message::error([],'修改失败');
        return Message::sucess([],'修改成功');
    }
    /**
     * 删除分组名
     * @RequestMapping(route="user/del",method={RequestMethod::GET})
     * @Strings(from=ValidateFrom::GET,name="id")
     * @param Request $request
     */
    public function delMyGroup($request)
    {
        $this->getCurrentUser();
        $res = $this->userGroupModel->delGroup($request->query('id'),$this->user);
        if($res)
            return Message::error([],'删除成功');
        return Message::error([],'删除失败');
    }
}