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
 * 群成员表

 * @Entity()
 * @Table(name="group_member")
 * @uses      GroupMember
 */
class GroupMember extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int $groupId 群id 外键
     * @Column(name="group_id", type="integer")
     * @Required()
     */
    private $groupId;

    /**
     * @var int $userId 用户id外键
     * @Column(name="user_id", type="integer")
     * @Required()
     */
    private $userId;

    /**
     * @var int $status 1正常 0 删除
     * @Column(name="status", type="tinyint", default=1)
     */
    private $status;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer")
     * @Required()
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
     * 群id 外键
     * @param int $value
     * @return $this
     */
    public function setGroupId(int $value): self
    {
        $this->groupId = $value;

        return $this;
    }

    /**
     * 用户id外键
     * @param int $value
     * @return $this
     */
    public function setUserId(int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * 1正常 0 删除
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
     * 群id 外键
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * 用户id外键
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 1正常 0 删除
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
