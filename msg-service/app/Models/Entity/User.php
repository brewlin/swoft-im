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
 * @Table(name="user")
 * @uses      User
 */
class User extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $email 
     * @Column(name="email", type="string", length=50, default="")
     */
    private $email;

    /**
     * @var int $number 
     * @Column(name="number", type="integer")
     * @Required()
     */
    private $number;

    /**
     * @var string $password 
     * @Column(name="password", type="string", length=255, default="")
     */
    private $password;

    /**
     * @var string $username 用户名
     * @Column(name="username", type="string", length=255, default="")
     */
    private $username;

    /**
     * @var string $nickname 昵称
     * @Column(name="nickname", type="string", length=50, default="")
     */
    private $nickname;

    /**
     * @var string $birthday 生日日期
     * @Column(name="birthday", type="string", length=50, default="")
     */
    private $birthday;

    /**
     * @var string $bloodType 血型
     * @Column(name="blood_type", type="string", length=50, default="")
     */
    private $bloodType;

    /**
     * @var string $job 职业
     * @Column(name="job", type="char", length=10, default="小白")
     */
    private $job;

    /**
     * @var int $qq qq号码
     * @Column(name="qq", type="integer", default=0)
     */
    private $qq;

    /**
     * @var string $wechat 微信号
     * @Column(name="wechat", type="string", length=50, default="")
     */
    private $wechat;

    /**
     * @var string $phone 手机号
     * @Column(name="phone", type="char", length=11, default="0")
     */
    private $phone;

    /**
     * @var string $sign 签名
     * @Column(name="sign", type="string", length=255, default="")
     */
    private $sign;

    /**
     * @var int $sex 性别
     * @Column(name="sex", type="tinyint", default=1)
     */
    private $sex;

    /**
     * @var string $avatar 头像
     * @Column(name="avatar", type="string", length=255, default="/timg.jpg")
     */
    private $avatar;

    /**
     * @var int $lastLogin 
     * @Column(name="last_login", type="integer")
     */
    private $lastLogin;

    /**
     * @var int $createTime 
     * @Column(name="create_time", type="integer")
     */
    private $createTime;

    /**
     * @var int $status 1正常 0 删除
     * @Column(name="status", type="tinyint", default=1)
     */
    private $status;

    /**
     * @var string $education 教育程度
     * @Column(name="education", type="string", length=255, default="")
     */
    private $education;

    /**
     * @var int $attention 关注人数
     * @Column(name="attention", type="tinyint", default=0)
     */
    private $attention;

    /**
     * @var int $love 点赞人数
     * @Column(name="love", type="tinyint", default=0)
     */
    private $love;

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
     * @param string $value
     * @return $this
     */
    public function setEmail(string $value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setNumber(int $value): self
    {
        $this->number = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPassword(string $value): self
    {
        $this->password = $value;

        return $this;
    }

    /**
     * 用户名
     * @param string $value
     * @return $this
     */
    public function setUsername(string $value): self
    {
        $this->username = $value;

        return $this;
    }

    /**
     * 昵称
     * @param string $value
     * @return $this
     */
    public function setNickname(string $value): self
    {
        $this->nickname = $value;

        return $this;
    }

    /**
     * 生日日期
     * @param string $value
     * @return $this
     */
    public function setBirthday(string $value): self
    {
        $this->birthday = $value;

        return $this;
    }

    /**
     * 血型
     * @param string $value
     * @return $this
     */
    public function setBloodType(string $value): self
    {
        $this->bloodType = $value;

        return $this;
    }

    /**
     * 职业
     * @param string $value
     * @return $this
     */
    public function setJob(string $value): self
    {
        $this->job = $value;

        return $this;
    }

    /**
     * qq号码
     * @param int $value
     * @return $this
     */
    public function setQq(int $value): self
    {
        $this->qq = $value;

        return $this;
    }

    /**
     * 微信号
     * @param string $value
     * @return $this
     */
    public function setWechat(string $value): self
    {
        $this->wechat = $value;

        return $this;
    }

    /**
     * 手机号
     * @param string $value
     * @return $this
     */
    public function setPhone(string $value): self
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * 签名
     * @param string $value
     * @return $this
     */
    public function setSign(string $value): self
    {
        $this->sign = $value;

        return $this;
    }

    /**
     * 性别
     * @param int $value
     * @return $this
     */
    public function setSex(int $value): self
    {
        $this->sex = $value;

        return $this;
    }

    /**
     * 头像
     * @param string $value
     * @return $this
     */
    public function setAvatar(string $value): self
    {
        $this->avatar = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLastLogin(int $value): self
    {
        $this->lastLogin = $value;

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
     * 教育程度
     * @param string $value
     * @return $this
     */
    public function setEducation(string $value): self
    {
        $this->education = $value;

        return $this;
    }

    /**
     * 关注人数
     * @param int $value
     * @return $this
     */
    public function setAttention(int $value): self
    {
        $this->attention = $value;

        return $this;
    }

    /**
     * 点赞人数
     * @param int $value
     * @return $this
     */
    public function setLove(int $value): self
    {
        $this->love = $value;

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 用户名
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * 昵称
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * 生日日期
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * 血型
     * @return string
     */
    public function getBloodType()
    {
        return $this->bloodType;
    }

    /**
     * 职业
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * qq号码
     * @return int
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * 微信号
     * @return string
     */
    public function getWechat()
    {
        return $this->wechat;
    }

    /**
     * 手机号
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * 签名
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * 性别
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * 头像
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return int
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @return int
     */
    public function getCreateTime()
    {
        return $this->createTime;
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
     * 教育程度
     * @return string
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * 关注人数
     * @return int
     */
    public function getAttention()
    {
        return $this->attention;
    }

    /**
     * 点赞人数
     * @return int
     */
    public function getLove()
    {
        return $this->love;
    }

}
