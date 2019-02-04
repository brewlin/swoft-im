<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:55
 */

namespace App\Controllers\Api;


use App\Exception\Http\RpcException;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\User\UserGroupMemberServiceInterface;
use ServiceComponents\Rpc\User\UserServiceInterface;
use Swoft\Bean\Annotation\CachePut;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserGroupMemberController
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im/user")
 */
class UserGroupMemberController extends BaseController
{
    /**
     * @Reference("userService")
     * @var UserGroupMemberServiceInterface
     */
    private $userGroupMemberService;
    /**
     * @Reference("userService")
     * @var UserServiceInterface
     */
    private $userService;
    /**
     * 编辑好友备注名
     * @RequestMapping(route="friend/remark",method={RequestMethod::POST})
     * @Strings(from=ValidatorFrom::POST,name="friend_id")
     * @Strings(from=ValidatorFrom::POST,name="friend_name")
     * @param Request $request
     */
    public function editFriendRemarkName($request)
    {
        $data = $request->input();
        $this->getCurrentUser();
        $res = $this->userGroupMemberService->editFriendRemarkName($this->user['id'] , $data['friend_id'] , $data['friend_name']);
        if($res)
            return Message::success($data['friend_name']);
        return Message::error('','修改失败');
    }
    /**
     * 移动好友分组
     * @RequestMapping(route="friend/move",method={RequestMethod::POST})
     * @Strings(from=ValidatorFrom::POST,name="friend_id")
     * @Strings(from=ValidatorFrom::POST,name="groupid")
     * @param Request $request
     */
    public function moveFriendToGroup($request)
    {
        $data = $request->post();
        $this->getCurrentUser();
        $userGroupRes = $this->userGroupMemberService->moveFriend($this->user['id'] , $data['friend_id'] , $data['groupid']);
        if($userGroupRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $res = $userGroupRes['data'];
        if($res)
        {
            //返回好友信息
            $userRes = $this->userService->getUserByCondition(['id' => $data['friend_id']]);
            if($userRes['code'] != StatusEnum::Success)
                throw new RpcException();
            $user = $userRes['data'];
            return Message::success($user);
        }
        return Message::error('','移动失败');
    }
    /**
     * 删除好友
     * @RequestMapping(route="friend/remove",method={RequestMethod::POST})
     * @Strings(from=ValidatorFrom::POST,name="friend_id")
     * @param Request $request
     */
    public function removeFriend($request)
    {
        $data = $request->post();
        $this->getCurrentUser();
        $userGroupRes = $this->userGroupMemberService->removeFriend($this->user['id'] , $data['friend_id']);
        if($userGroupRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $res = $userGroupRes['data'];
        if($res)
            return Message::success('','删除成功');
        return Message::error('','修改失败');
    }
    /**
     * 获取推荐好友
     * @RequestMapping(route="friend/recommend",method={RequestMethod::GET})
     */
    public function getRecommendFriend()
    {
        //获取所有好友
        $userRes = $this->userService->getAllUser();
        if($userRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $list = $userRes['data'];
        //去除已经是本人的好友关系
        return Message::success($list);
    }
}