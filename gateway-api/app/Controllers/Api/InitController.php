<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:50
 */

namespace App\Controllers\Api;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserGroupModelInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class InitController
 * @package App\Controllers\Api
 * @Controller("api/im")
 */
class InitController
{
    /**
     * @Reference("userService")
     * @var UserCacheInterface
     */
    private $userCache;
    /**
     * @Reference("userService")
     * @var UserGroupModelInterface
     */
    private $userGroupModel;
    /**
     * @Reference("userService")
     * @var UserGroupMemberServiceInterface
     */
    private $userGroupMemberService;
    /**
     * @Reference("groupService")
     * @var GroupMemberModelInterface
     */
    private $groupMemberModel;
    /**
     * @RequestMapping(route="init")
     * @param Request $request
     */
    public function init($request)
    {
        //获取自己信息
        $token = $request->input('token');
        $user = $this->userCache->getUserByToken($token);
        $user['status'] = 'online';
        // 获取分组好友
        $friends = $this->userGroupModel->getAllFriends($user['id']);
        $data = $this->userGroupMemberService->getFriends($friends);
        //获取群组信息
        $groups = $this->groupMemberModel->getGroupNames(['user_number'=>$user['number'],'status' => 1]);
        return Message::sucess(['mine' => $user ,'friend' => $data, 'group' => $groups?$groups:[]]);
    }
}