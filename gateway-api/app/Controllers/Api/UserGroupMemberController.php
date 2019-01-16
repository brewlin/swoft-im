<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:55
 */

namespace App\Controllers\Api;


use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\User\UserGroupMemberModelInterface;
use Swoft\Bean\Annotation\Strings;
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
     * @var UserGroupMemberModelInterface
     */
    private $userGroupMemberModel;
    /**
     * 编辑好友备注名
     * @RequestMapping(route="friend/remark",method={RequestMethod::POST})
     * @Strings(from=ValidateFrom::POST,name="friend_id")
     * @Strings(from=ValidateFrom::POST,name="friend_name")
     * @param Request $request
     */
    public function editFriendRemarkName($request)
    {
        $data = $request->input();
        $this->getCurrentUser();
        $res = $this->userGroupMemberModel->editFriendRemarkName($this->user['id'] , $data['friend_id'] , $data['friend_name']);
        if($res)
            return Message::sucess($data['friend_name']);
        return Message::error('','修改失败');
    }
    /**
     * 移动好友分组
     */
    public function moveFriendToGroup()
    {
        (new GroupUserMemberValidate('move'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserMemberModel::moveFriend($this->user['id'] , $data['friend_id'] , $data['groupid']);
        if($res)
        {
            //返回好友信息
            $user = User::getUser(['id' => $data['friend_id']]);
            return $this->success($user);
        }
        return $this->error('','移动失败');
    }
    /**
     * 删除好友
     */
    public function removeFriend()
    {
        (new GroupUserMemberValidate('remove'))->goCheck($this->request());
        $data = $this->request()->getRequestParam();
        $res = GroupUserMemberModel::removeFriend($this->user['id'] , $data['friend_id']);
        if($res)
        {
            return $this->success('','删除成功');
        }
        return $this->error('','修改成功');
    }
    /**
     * 获取推荐好友
     */
    public function getRecommendFriend()
    {
        //获取所有好友
        $list = User::getAllUser();
        //去除已经是本人的好友关系
        return $this->success($list);

    }
}