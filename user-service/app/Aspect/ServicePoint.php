<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/28
 * Time: 20:05
 */

namespace App\Aspect;
use App\Services\LoginService;
use App\Services\Models\UserGroupMemberModel;
use App\Services\Models\UserGroupModel;
use App\Services\Models\UserModel;
use App\Services\Models\UserRecordModel;
use App\Services\UserGroupMemberService;
use App\Services\UserRecordService;
use App\Services\UserService;
use Swoft\Aop\JoinPoint;
use Swoft\Aop\ProceedingJoinPoint;
use Swoft\Bean\Annotation\After;
use Swoft\Bean\Annotation\AfterReturning;
use Swoft\Bean\Annotation\Around;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\Before;
use Swoft\Bean\Annotation\PointBean;
use Swoft\Bean\Annotation\PointExecution;

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
        UserRecordService::class,
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