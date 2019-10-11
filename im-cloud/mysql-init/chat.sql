/*
Navicat MySQL Data Transfer

Source Database       : chat

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2019-02-07 09:28:52
*/
-- 创建数据库
 DROP database IF EXISTS `chat`;
 create database `chat` default character set utf8 collate utf8_general_ci;
 use chat;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for group
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL COMMENT '群号 id',
  `user_id` int(11) NOT NULL COMMENT '创建人',
  `groupname` varchar(255) NOT NULL DEFAULT '' COMMENT '群名称',
  `avatar` varchar(255) NOT NULL DEFAULT '/timg.jpg' COMMENT '群头像',
  `groupinfo` varchar(255) NOT NULL DEFAULT '' COMMENT '群简介',
  `approval` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否需要验证加群',
  `group_size` int(11) DEFAULT '200' COMMENT '加群人数',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1正常 0 删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='群组基础表';

-- ----------------------------
-- Table structure for group_member
-- ----------------------------
DROP TABLE IF EXISTS `group_member`;
CREATE TABLE `group_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL COMMENT '群id 外键',
  `user_id` int(11) NOT NULL COMMENT '用户id外键',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1正常 0 删除',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='群成员表';

-- ----------------------------
-- Table structure for group_record
-- ----------------------------
DROP TABLE IF EXISTS `group_record`;
CREATE TABLE `group_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户外键',
  `group_id` int(10) unsigned NOT NULL COMMENT '群外键',
  `content` varchar(500) NOT NULL COMMENT '内容',
  `create_time` int(11) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '正常1 删除 0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for msg
-- ----------------------------
DROP TABLE IF EXISTS `msg`;
CREATE TABLE `msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL COMMENT '消息类型 1好友添加 2系统消息3 加群消息',
  `from` int(11) NOT NULL COMMENT '消息发送方的id',
  `to` int(11) NOT NULL COMMENT '消息接受方id',
  `user_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组id',
  `handle` varchar(50) DEFAULT NULL COMMENT '群管理员名称',
  `groupname` varchar(50) DEFAULT NULL COMMENT '群名称',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '消息状态 2 统一好友申请 4 拒绝好友申请',
  `remark` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `read_time` int(11) NOT NULL DEFAULT '0' COMMENT '阅读时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for suggestion
-- ----------------------------
DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE `suggestion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id外键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
  `birthday` varchar(50) DEFAULT '' COMMENT '生日日期',
  `blood_type` varchar(50) DEFAULT '' COMMENT '血型',
  `job` char(10) DEFAULT '小白' COMMENT '职业',
  `qq` int(15) DEFAULT '0' COMMENT 'qq号码',
  `wechat` varchar(50) DEFAULT '' COMMENT '微信号',
  `phone` char(11) DEFAULT '0' COMMENT '手机号',
  `sign` varchar(255) DEFAULT '' COMMENT '签名',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别',
  `avatar` varchar(255) DEFAULT '/timg.jpg' COMMENT '头像',
  `last_login` int(11) DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1正常 0 删除',
  `education` varchar(255) DEFAULT '' COMMENT '教育程度',
  `attention` tinyint(4) DEFAULT '0' COMMENT '关注人数',
  `love` tinyint(4) DEFAULT '0' COMMENT '点赞人数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number_union` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_group
-- ----------------------------
DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id外键',
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分组名',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0 删除 1 正常',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='用户分组';

-- ----------------------------
-- Table structure for user_group_member
-- ----------------------------
DROP TABLE IF EXISTS `user_group_member`;
CREATE TABLE `user_group_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) unsigned NOT NULL COMMENT '好友分组外键',
  `friend_id` int(11) unsigned NOT NULL COMMENT '好友外键id',
  `remark_name` varchar(50) NOT NULL DEFAULT '' COMMENT '备注名',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0 删除 1 正常',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `好友只能存在一个` (`user_id`,`friend_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='好友分组里的成员';

-- ----------------------------
-- Table structure for user_record
-- ----------------------------
DROP TABLE IF EXISTS `user_record`;
CREATE TABLE `user_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `friend_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT '1' COMMENT '1已读 0 未读',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1正常 0 删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
