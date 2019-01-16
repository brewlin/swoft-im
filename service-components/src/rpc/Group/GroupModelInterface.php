<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 上午 9:30
 */

namespace ServiceComponents\Rpc\Group;


interface GroupModelInterface
{

    public function getSum($where);

    public function getGroup($where, $single = false);
    public function getGroupOwnById($id,$key = null);

    public function newGroup($data);
    public function getGroupOwner($id);
    public function getNumberById($id);
    /**
     * 查找群
     */
    public function searchGroup($value ,$page = null);

}