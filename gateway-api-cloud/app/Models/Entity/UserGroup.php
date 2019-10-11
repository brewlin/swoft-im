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
 * 用户分组

 * @Entity()
 * @Table(name="user_group")
 * @uses      UserGroup
 */
class UserGroup extends Model
{
    /**
     * @var int $id 主键id
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int $userId 用户id外键
     * @Column(name="user_id", type="integer")
     * @Required()
     */
    private $userId;

    /**
     * @var string $groupName 分组名
     * @Column(name="group_name", type="string", length=255, default="")
     */
    private $groupName;

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
     * 主键id
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

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
     * 分组名
     * @param string $value
     * @return $this
     */
    public function setGroupName(string $value): self
    {
        $this->groupName = $value;

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
     * 主键id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * 分组名
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
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
