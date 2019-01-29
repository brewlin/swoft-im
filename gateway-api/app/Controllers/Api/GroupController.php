<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:38
 */

namespace App\Controllers\Api;
use App\Exception\Http\GroupException;
use App\Exception\Http\RpcException;
use App\Middlewares\TokenCheckMiddleware;
use ServiceComponents\Common\Common;
use ServiceComponents\Common\Message;
use ServiceComponents\Enum\StatusEnum;
use ServiceComponents\Rpc\Group\GroupMemberModelInterface;
use ServiceComponents\Rpc\Group\GroupModelInterface;
use ServiceComponents\Rpc\Group\GroupServiceInterface;
use ServiceComponents\Rpc\Redis\UserCacheInterface;
use ServiceComponents\Rpc\User\UserModelInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
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
     * @var GroupServiceInterface
     */
    private $groupService;
    /**
     * @Reference("redisCache")
     * @var UserCacheInterface
     */
    private $userCacheService;
    /**
     * @RequestMapping(route="/api/im/members",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function getMembers($request)
    {
        $id = $request->query('id');
        //调用群组服务 获取群信息
        $groupRes = $this->groupService->getGroupMembers($id);
        if($groupRes['code'] != StatusEnum::Success)
            throw new RpcException();

        return Message::success($groupRes['data']);
    }
    /**
     * 离开群组
     * @RequestMapping(route="group/leave",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @param Request $request
     */
    public function leaveGroup($request)
    {
        $this->getCurrentUser();
        $number = $this->user['number'];
        $id = $request->query('id');

        //调用群组服务 退出群组
        $groupRes = $this->groupService->leaveGroup($id,$number);

        if($groupRes['code'] != StatusEnum::Success)
            return Message::error('','退出失败');
        return Message::success('','退出成功');
    }
    /**
     * 检查用户是否可以继续创建群
     * @RequestMapping(route="group/check",method={RequestMethod::GET})
     */
    public function checkUserCreateGroup()
    {
        $this->getCurrentUser();
        //调用群组服务 获取群组信息
        $groupRes = $this->groupService->getGroupByCondition(['user_number' => $this->user['number']]);
        if($groupRes['code'] != StatusEnum::Success)
            throw new RpcException();
        $list = $groupRes['data'];
        if(count($list) > 50)
            return Message::error('','超过最大建群数量');
        return Message::success();
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

        //调用群组服务 创建群
        $groupRes = $this->groupService->createGroup($data,$number,$this->user['number']);
        if($groupRes['code'] != StatusEnum::Success)
            throw new RpcException();

        $id = $groupRes['data'];
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

        return Message::success(['groupid' => $number,'groupName' => $data['groupName']]);
    }
}