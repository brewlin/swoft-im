<?php
namespace App\Models\Entity;

use Swoft\Db\Model;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Types;

/**
 * 好友分组里的成员

 * @Entity()
 * @Table(name="user_group_member")
 * @uses      UserGroupMember
 */
class UserGroupMember extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int $userId 
     * @Column(name="user_id", type="integer")
     * @Required()
     */
    private $userId;

    /**
     * @var int $userGroupId 好友分组外键
     * @Column(name="user_group_id", type="integer")
     * @Required()
     */
    private $userGroupId;

    /**
     * @var int $friendId 好友外键id
     * @Column(name="friend_id", type="integer")
     * @Required()
     */
    private $friendId;

    /**
     * @var string $remarkName 备注名
     * @Column(name="remark_name", type="string", length=50, default="")
     */
    private $remarkName;

    /**
     * @var int $status 状态 0 删除 1 正常
     * @Column(name="status", type="integer", default=1)
     */
    private $status;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer")
     */
    private $createTime;

    /**
     * @var int $updateTime 
     * @Column(name="update_time", type="integer")
     */
    private $updateTime;

    /**
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setUserId(int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * 好友分组外键
     * @param int $value
     * @return $this
     */
    public function setUserGroupId(int $value): self
    {
        $this->userGroupId = $value;

        return $this;
    }

    /**
     * 好友外键id
     * @param int $value
     * @return $this
     */
    public function setFriendId(int $value): self
    {
        $this->friendId = $value;

        return $this;
    }

    /**
     * 备注名
     * @param string $value
     * @return $this
     */
    public function setRemarkName(string $value): self
    {
        $this->remarkName = $value;

        return $this;
    }

    /**
     * 状态 0 删除 1 正常
     * @param int $value
     * @return $this
     */
    public function setStatus(int $value): self
    {
        $this->status = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCreateTime(int $value): self
    {
        $this->createTime = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setUpdateTime(int $value): self
    {
        $this->updateTime = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 好友分组外键
     * @return int
     */
    public function getUserGroupId()
    {
        return $this->userGroupId;
    }

    /**
     * 好友外键id
     * @return int
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * 备注名
     * @return string
     */
    public function getRemarkName()
    {
        return $this->remarkName;
    }

    /**
     * 状态 0 删除 1 正常
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

}
