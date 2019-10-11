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
 * 群组基础表

 * @Entity()
 * @Table(name="group")
 * @uses      GroupController
 */
class Group extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int $number 群号 id
     * @Column(name="number", type="integer")
     * @Required()
     */
    private $number;

    /**
     * @var int $userId 创建人
     * @Column(name="user_id", type="integer")
     * @Required()
     */
    private $userId;

    /**
     * @var string $groupname 群名称
     * @Column(name="groupname", type="string", length=255, default="")
     */
    private $groupname;

    /**
     * @var string $avatar 群头像
     * @Column(name="avatar", type="string", length=255, default="/timg.jpg")
     */
    private $avatar;

    /**
     * @var string $groupinfo 群简介
     * @Column(name="groupinfo", type="string", length=255, default="")
     */
    private $groupinfo;

    /**
     * @var int $approval 是否需要验证加群
     * @Column(name="approval", type="tinyint", default=1)
     */
    private $approval;

    /**
     * @var int $groupSize 加群人数
     * @Column(name="group_size", type="integer", default=200)
     */
    private $groupSize;

    /**
     * @var int $status 1正常 0 删除
     * @Column(name="status", type="tinyint", default=1)
     */
    private $status;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer")
     */
    private $createTime;

    /**
     * @var int $updateTime 更新时间
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
     * 群号 id
     * @param int $value
     * @return $this
     */
    public function setNumber(int $value): self
    {
        $this->number = $value;

        return $this;
    }

    /**
     * 创建人
     * @param int $value
     * @return $this
     */
    public function setUserId(int $value): self
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * 群名称
     * @param string $value
     * @return $this
     */
    public function setGroupname(string $value): self
    {
        $this->groupname = $value;

        return $this;
    }

    /**
     * 群头像
     * @param string $value
     * @return $this
     */
    public function setAvatar(string $value): self
    {
        $this->avatar = $value;

        return $this;
    }

    /**
     * 群简介
     * @param string $value
     * @return $this
     */
    public function setGroupinfo(string $value): self
    {
        $this->groupinfo = $value;

        return $this;
    }

    /**
     * 是否需要验证加群
     * @param int $value
     * @return $this
     */
    public function setApproval(int $value): self
    {
        $this->approval = $value;

        return $this;
    }

    /**
     * 加群人数
     * @param int $value
     * @return $this
     */
    public function setGroupSize(int $value): self
    {
        $this->groupSize = $value;

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
     * 更新时间
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
     * 群号 id
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * 创建人
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 群名称
     * @return string
     */
    public function getGroupname()
    {
        return $this->groupname;
    }

    /**
     * 群头像
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * 群简介
     * @return string
     */
    public function getGroupinfo()
    {
        return $this->groupinfo;
    }

    /**
     * 是否需要验证加群
     * @return mixed
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * 加群人数
     * @return mixed
     */
    public function getGroupSize()
    {
        return $this->groupSize;
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
     * 更新时间
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

}
