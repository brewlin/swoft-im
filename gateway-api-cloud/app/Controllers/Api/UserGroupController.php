<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:54
 */

namespace App\Controllers\Api;
use App\Exception\Http\RpcException;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\User\UserGroupServiceInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
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
     * @var UserGroupServiceInterface
     */
    private $userGroupService;
    /**
     * 分组名添加
     * @RequestMapping(route="user/add",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="token")
     * @Strings(from=ValidatorFrom::GET,name="groupname")
     * @param Request $request
     */
    public function addMyGroup($request)
    {
        $token = $request->query('token');
        $groupname = $request->query('groupname');

        //调用user服务
        $userRes = $this->userGroupService->addUserGroup($token,$groupname);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
       $groupId = $userRes['data'];
        return Message::success(['id' => $groupId , 'groupname' => $groupname]);
    }
    /**
     * 分组名修改
     * @RequestMapping(route="user/edit",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @Strings(from=ValidatorFrom::GET,name="groupname")
     * @param Request $request
     */
    public function editMyGroup($request)
    {
        $data = $request->query();

        //调用用户服务
        $userRes = $this->userGroupService->updateByCondition(['groupname' => $data['groupname']],['id' => $data['id']]);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $res = $userRes['data'];
        if(!$res)
            return Message::error([],'修改失败');
        return Message::success([],'修改成功');
    }
    /**
     * 删除分组名
     * @RequestMapping(route="user/del",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function delMyGroup($request)
    {
        $this->getCurrentUser();
        $userRes = $this->userGroupService->delGroup($request->query('id'),$this->user);
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $res = $userRes['data'];
        if($res)
            return Message::error([],'删除成功');
        return Message::error([],'删除失败');
    }
}