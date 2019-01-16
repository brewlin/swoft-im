<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:38
 */

namespace App\Controllers\Api;
use App\Exception\Http\GroupException;
use App\Middlewares\TokenCheckMiddleware;
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class Group
 * @package App\Controllers\Api
 * @Controller(prefix="api/im/team")
 * @Middleware(class=TokenCheckMiddleware::class)
 */
class GroupController extends BaseController
{
    /**
     * @Reference("groupService")
     * @var GroupModelInterface
     */
    private $groupModel;
    /**
     * @Reference("groupService")
     * @var GroupMemberModelInterface
     */
    private $groupMemberModel;
    /**
     * @Reference("userService")
     * @var UserModelInterface
     */
    private $userModel;
    /**
     * @Reference("redisCache")
     * @var UserCacheInterface
     */
    private $userCacheService;
    /**
     * @RequestMapping(route="/api/im/members",method={RequestMethod::GET})
     * @param Request $request
     */
    public function getMembers($request)
    {
        //获取群信息
        $id = $request->query('id');
        $owner = $this->groupModel->getGroupOwner($id);
        //获取群成员
        $memberList = $this->groupMemberModel->getGroupMembers($owner['gnumber']);
        $list = $this->userModel->getUserByNumbers($memberList);
        return Message::sucess(compact('owner','list'));
    }
    /**
     * 离开群组
     * @RequestMapping(route="group/leave",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function leaveGroup($request)
    {
        $groupNumber = $this->groupMemberModel->getNumberById($request->query('id'));
        $this->getCurrentUser();
        $res = $this->groupMemberModel->delMemberById($this->user['number'] , $groupNumber);
        if(!$res)
            return Message::error('','退出失败');
        return Message::sucess('','退出成功');
    }
    /**
     * 检查用户是否可以继续创建群
     * @RequestMapping(route="group/check",method={RequestMethod::GET})
     */
    public function checkUserCreateGroup()
    {
        $this->getCurrentUser();
        $list = $this->groupModel->getGroup(['user_number' => $this->user['number']]);
        if(count($list) > 50)
            return Message::error('','超过最大建群数量');
        return Message::sucess();
    }
    /**
     * 创建群
     * @RequestMapping(route="group/create",method={RequestMethod::POST})
     * @Strings(from=ValidatorFrom::POST,name="groupName")
     * @Strings(from=ValidatorFrom::POST,name="des")
     * @Strings(from=ValidatorFrom::POST,name="number")
     * @Strings(from=ValidatorFrom::POST,name="approval")
     * @param Request $request
     */
    public function createGroup($request)
    {
        $data = $request->post();
        $this->getCurrentUser();
        // 生成唯一群号
        $number = Common::generate_code(8);
        // 保存群信息，并加入群
        $group_data = [
            'gnumber'       => $number,
            'user_number'   => $this->user['number'],
            'ginfo'         => $data['des'],
            'gname'         => $data['des'],
            'groupname' => $data['groupName'],//群名称
            'approval' => $data['approval'],//验证方式 需要验证 不需要验证
            'number' => $data['number'],//群上限人数
        ];
        $member_data = [
            'gnumber'       => $number,
            'user_number'   => $this->user['number'],
        ];
        $id =  $this->groupModel->newGroup($group_data);
        $res = $this->groupMemberModel->newGroupMember($member_data);
        if(!$res)
            throw new GroupException(['msg' => '创建群失败']);
        $sendData  = [
            'id'            => $id,
            'avatar'         => '/timg.jpg',
            'groupname'     => $data['groupName'],
            'type'          => 'group',
            'gnumber'       => $number

        ];
        // 创建缓存
        $this->userCacheService->setGroupFds($number, $this->user['fd']);
        $server = \Swoft::$server;
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'newGroup','data'=> $sendData]));
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'ok','data'=> '创建成功']));
        return Message::sucess(['groupid' => $number,'groupName' => $data['groupName']]);
    }
}