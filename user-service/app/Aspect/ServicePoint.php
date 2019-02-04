<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/28
 * Time: 20:05
 */

namespace App\Aspect;
use Swoft\Aop\JoinPoint;
use Swoft\Bean\Annotation\AfterReturning;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\PointBean;

/**
 * 全局返回值前进行切入
 * Class ServicePoint
 * @package App\Aspect
 * @Aspect()
 * @PointBean(include={
        UserGroupMemberModel::class,
        UserGroupModel::class,
        UserModel::Class,
        UserRecordModel::class,
        LoginService::class,
        UserGroupMemberService::class,
        RecordService::class,
        UserService::class
 *     })
 * )
 */
class ServicePoint
{
    /**
     * @AfterReturning()
     * @param JoinPoint $joinPoint
     * @return string
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
        $result = $joinPoint->getReturn();
        if(is_null($result))
            return '';
        return $result;
    }
}