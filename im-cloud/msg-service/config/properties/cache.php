<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'redis'     => [
        'name'        => 'redis',
        'uri'         => [
            '127.0.0.1:6379',
            '127.0.0.1:6379',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'maxWaitTime' => 3,
        'maxIdleTime' => 60,
        'timeout'     => 8,
        'db'          => 0,
        'prefix'      => '',
        'serialize'   => 0,
    ],
    'redisCache' => [
        'db'     => 0,
        'prefix' => '',
    ],
    'cacheName' => [
        'token_user' => 'user:token:%s:data',                           //  token   => user 信息， 用户登录时创建
        'number_userOtherInfo' =>'userOtherInfo:%s:number:data',        //  number  => fd，token 等信息，用户登录时创建
        'fd_token' => 'fd:%s:data',                                     //  fd => token,    用户登录时创建
        'friend_req' => 'friend:fromNumber:%s:data',                    //  from_num => to_num，发送好友请求时创建，对方拒绝或同意时销毁
        'group_number_fd' => 'group:number:%s:data',                    //  gnumber => fds，创建新群组时创建
    ]
];