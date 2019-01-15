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
 * @Table(name="user_record")
 * @uses      UserRecord
 */
class UserRecord extends Model
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
     * @var int $friendId 
     * @Column(name="friend_id", type="integer")
     * @Required()
     */
    private $friendId;

    /**
     * @var string $content 
     * @Column(name="content", type="text", length=65535)
     * @Required()
     */
    private $content;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer")
     */
    private $createTime;

    /**
     * @var int $isRead 1已读 0 未读
     * @Column(name="is_read", type="tinyint", default=1)
     */
    private $isRead;

    /**
     * @var int $status 1正常 0 删除
     * @Column(name="status", type="tinyint", default=1)
     */
    private $status;

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
     * @param int $value
     * @return $this
     */
    public function setFriendId(int $value): self
    {
        $this->friendId = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setContent(string $value): self
    {
        $this->content = $value;

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
     * 1已读 0 未读
     * @param int $value
     * @return $this
     */
    public function setIsRead(int $value): self
    {
        $this->isRead = $value;

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
     * @return int
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * 1已读 0 未读
     * @return mixed
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * 1正常 0 删除
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

}
