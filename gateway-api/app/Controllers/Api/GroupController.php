<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:38
 */

namespace App\Controllers\Api;
use App\Middlewares\TokenCheckMiddleware;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
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
     */
    public function leaveGroup()
    {
        (new GroupMemberValidate('leave'))->goCheck($this->request());
        $groupNumber = GroupMemberModel::getNumberById($this->request()->getRequestParam('id'));
        $res = GroupMemberModel::delMemberById($this->user['number'] , $groupNumber);
        if(!$res)
        {
            return $this->error('','退出失败');
        }
        return $this->success('','退出成功');
    }
    /**
     * 检查用户是否可以继续创建群
     * @RequestMapping(route="group/check",method={RequestMethod::GET})
     */
    public function checkUserCreateGroup()
    {
        $list = Group::getGroup(['user_number' => $this->user['number']]);
        if(count($list) > 50)
        {
            return $this->error('','超过最大建群数');
        }
        return $this->success();
    }
    /**
     * 创建群
     * @RequestMapping(route="group/create",method={RequestMethod::POST})
     */
    public function createGroup()
    {
        (new GroupMemberValidate('create'))->goCheck($this->request());
        $data = $this->request()->getParsedBody();
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
        try{
            $id =  Group::newGroup($group_data);
            GroupMemberModel::newGroupMember($member_data);
        }catch (\Exception $e){
            Logger::getInstance()->log($e->getMessage(),'LTalk_debug');
            $msg = (new WsException())->getMsg();
            return $this->error(null,$msg);
        }
        $sendData  = [
            'id'            => $id,
            'avatar'         => '/timg.jpg',
            'groupname'     => $data['groupName'],
            'type'          => 'group',
            'gnumber'       => $number

        ];
        // 创建缓存
        UserCacheService::setGroupFds($number, $this->user['fd']);
        $server = ServerManager::getInstance()->getServer();
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'newGroup','data'=> $sendData]));
        $server->push($this->user['fd'] , json_encode(['type'=>'ws','method'=> 'ok','data'=> '创建成功']));
        return $this->success(['groupid' => $number,'groupName' => $data['groupName']],'');

    }
}