/*
Navicat MySQL Data Transfer

Source Server         : 192.168.200.120
Source Server Version : 50634
Source Host           : 192.168.200.120:3306
Source Database       : gearman

Target Server Type    : MYSQL
Target Server Version : 50634
File Encoding         : 65001

Date: 2016-11-10 16:21:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wd_video_convert
-- ----------------------------
DROP TABLE IF EXISTS `wd_video_convert`;
CREATE TABLE `wd_video_convert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) DEFAULT '0' COMMENT '应用id 默认是当前应用',
  `rowkey` varchar(50) NOT NULL COMMENT '应用主键值',
  `sourse_url` varchar(200) NOT NULL COMMENT '转码前地址',
  `convert_url` varchar(200) DEFAULT NULL COMMENT '转码后地址',
  `convert_size` int(11) DEFAULT NULL COMMENT '转码后大小(kb)',
  `convert_status` tinyint(4) DEFAULT '0' COMMENT '处理状态：0:转码中，1：转码成功，-1：转码失败',
  `convert_time` int(11) DEFAULT NULL COMMENT '转码成功时间',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `remark` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1637 DEFAULT CHARSET=utf8;
