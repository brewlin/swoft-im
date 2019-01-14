基于Swoft-cloud微服务架构-Im通讯平台
==============
概述
=======
+ 对swoole-im进行服务拆分，进行微服务架构(https:://wwww.github.com/Lxido/swoole-im.git)
+ 基于Swoft-cloud 进行服务化治理服务治理、熔断器、服务降级、Rpc调用、服务网关、
Cosul服务注册与发现、Mysql连接池、Redis连接池、异步任务、websocket推送
+ 底层采用Swoole通讯引擎,多进程、异步任务、协程
+ 服务间配置独立，使用composer进行依赖管理，进行composer组件化开发，公用的Rpc接口封
装为独立composer包。
    - 拆分有群组Rpc服务，聊天日志Rpc服务，用户基础Rpc服务，消息处理服务
    - Httpserver网关api服务，websocket服务
+ 请使用swoole扩展2.1.3 以及php 7.1
+ 快速开始
    - 针对每个服务使用composer更新依赖`composer update`
    - 开启所有服务 `make startAll`
+ 演示地址 http://im.huido.site 可以注册



架构图
=========
服务依赖
-----
![](./resource/services.png)

前端服务
-----
![](./resource/api.png)

服务处理
------
![](./resource/swoole.png)

服务开发
-----------
Gateway-Api && Websocket 中心网关服务
-------
    接受web端webocket长连接通讯、api请求.
    处理基础数据，对外中心api网关.
    服务调用方,调用群组服务、用户基础服务等`
- 依赖: user-service group-service services-components,redis-services
- 配置: `worker`:2，`task_worke`r:2,`port`:8090`熔断器`，`服务降级`,Rpc`连接池`，`useProvider`:false,
- 服务启动：

    `./gateway-api` 
    
    `composer udpate`更新依赖
    
    `php bin/swoft ws:start` --d可选守护进程模式
    
Redis 中心网关服务
-------

开发进度 && 实现功能
==========
> 好友单聊

> 添加好友

> 发送图片 文件视频等。。并解析

> 群聊

> websocket token 机制

> 分组添加 分组名（修改，删除 移动好友）

> 好友右键菜单操作功能

    - 发送好友信息
    - 查看好友资料
    - 查看好友聊天记录
    - 好友备注功能
    - 移动好友分组
    - 删除好友功能
> 发现中心

    - 搜索好友
    - 推荐好友 添加好友
> 消息中心

    - 好友离线上线通知
    - 系统消息推送
    - 好友添加申请通知 以及交互操作
7.预览
======
- 消息处理中心，消息盒子
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106210353.png?raw=true)
- 发现中心，推荐好友群，搜索好友群，创建群
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106210451.png?raw=true)
- 单聊，群聊 聊天界面，聊天记录
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106210543.png?raw=true)
- 主面板
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106210553.png?raw=true)
- 右键功能（好友管理，分组管理，群管理）
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106212344.png?raw=true)
- 整体预览图
![](https://github.com/Lxido/swoole-im/blob/master/img/QQ%E6%88%AA%E5%9B%BE20190106210500.png?raw=true)

