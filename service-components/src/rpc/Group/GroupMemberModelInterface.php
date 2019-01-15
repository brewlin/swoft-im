<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/15
 * Time: 22:57
 */

namespace ServiceComponents\Rpc\Group;


interface GroupMemberModelInterface
{
    public function newGroupMember($data);


    public function getGroups($where);

    public function getOneByWhere($where);

    public function getGroupNames($where);

    public function getGroupMembers($gnumber);
    /**
     * 删除群成员
     * @param $userNumber
     * @param $groupNumber
     */
    public function delMemberById($userNumber , $groupNumber);

    public function getNumberById($id);

    public function getIdByNumber($number);

}