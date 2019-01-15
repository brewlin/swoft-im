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
 * @Entity()
 * @Table(name="msg")
 * @uses      Msg
 */
class Msg extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int $type 消息类型 1好友添加 2系统消息3 加群消息
     * @Column(name="type", type="integer")
     * @Required()
     */
    private $type;

    /**
     * @var int $from 消息发送方的id
     * @Column(name="from", type="integer")
     * @Required()
     */
    private $from;

    /**
     * @var int $to 消息接受方id
     * @Column(name="to", type="integer")
     * @Required()
     */
    private $to;

    /**
     * @var int $userGroupId 分组id
     * @Column(name="user_group_id", type="integer", default=0)
     */
    private $userGroupId;

    /**
     * @var string $handle 群管理员名称
     * @Column(name="handle", type="string", length=50)
     */
    private $handle;

    /**
     * @var string $groupname 群名称
     * @Column(name="groupname", type="string", length=50)
     */
    private $groupname;

    /**
     * @var int $status 消息状态 2 统一好友申请 4 拒绝好友申请
     * @Column(name="status", type="integer", default=0)
     */
    private $status;

    /**
     * @var string $remark 备注
     * @Column(name="remark", type="string", length=100, default="")
     */
    private $remark;

    /**
     * @var int $sendTime 发送时间
     * @Column(name="send_time", type="integer", default=0)
     */
    private $sendTime;

    /**
     * @var int $readTime 阅读时间
     * @Column(name="read_time", type="integer", default=0)
     */
    private $readTime;

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
     * 消息类型 1好友添加 2系统消息3 加群消息
     * @param int $value
     * @return $this
     */
    public function setType(int $value): self
    {
        $this->type = $value;

        return $this;
    }

    /**
     * 消息发送方的id
     * @param int $value
     * @return $this
     */
    public function setFrom(int $value): self
    {
        $this->from = $value;

        return $this;
    }

    /**
     * 消息接受方id
     * @param int $value
     * @return $this
     */
    public function setTo(int $value): self
    {
        $this->to = $value;

        return $this;
    }

    /**
     * 分组id
     * @param int $value
     * @return $this
     */
    public function setUserGroupId(int $value): self
    {
        $this->userGroupId = $value;

        return $this;
    }

    /**
     * 群管理员名称
     * @param string $value
     * @return $this
     */
    public function setHandle(string $value): self
    {
        $this->handle = $value;

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
     * 消息状态 2 统一好友申请 4 拒绝好友申请
     * @param int $value
     * @return $this
     */
    public function setStatus(int $value): self
    {
        $this->status = $value;

        return $this;
    }

    /**
     * 备注
     * @param string $value
     * @return $this
     */
    public function setRemark(string $value): self
    {
        $this->remark = $value;

        return $this;
    }

    /**
     * 发送时间
     * @param int $value
     * @return $this
     */
    public function setSendTime(int $value): self
    {
        $this->sendTime = $value;

        return $this;
    }

    /**
     * 阅读时间
     * @param int $value
     * @return $this
     */
    public function setReadTime(int $value): self
    {
        $this->readTime = $value;

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
     * 消息类型 1好友添加 2系统消息3 加群消息
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 消息发送方的id
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * 消息接受方id
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * 分组id
     * @return int
     */
    public function getUserGroupId()
    {
        return $this->userGroupId;
    }

    /**
     * 群管理员名称
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
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
     * 消息状态 2 统一好友申请 4 拒绝好友申请
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 备注
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * 发送时间
     * @return int
     */
    public function getSendTime()
    {
        return $this->sendTime;
    }

    /**
     * 阅读时间
     * @return int
     */
    public function getReadTime()
    {
        return $this->readTime;
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
