-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-14 10:47:50
-- 服务器版本： 5.7.18
-- PHP Version: 5.5.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cangku`
--

-- --------------------------------------------------------

--
-- 表的结构 `wuliu`
--

CREATE TABLE IF NOT EXISTS `wuliu` (
  `id` int(7) unsigned NOT NULL,
  `code` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(120) NOT NULL DEFAULT '',
  `sendtype` int(5) NOT NULL DEFAULT '1' COMMENT '0为快递，1为自提',
  `desc` text NOT NULL,
  `dispatch_web` varchar(128) NOT NULL COMMENT '快递的首页',
  `configs` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wuliu`
--

INSERT INTO `wuliu` (`id`, `code`, `name`, `sendtype`, `desc`, `dispatch_web`, `configs`, `enabled`, `sort`) VALUES
(2, 'quanyikuaidi', '配送到家', 0, '配送到家', '', '', 0, 50),
(4, 'zhongtong', '中通速递', 0, '中通速递', '', '', 1, 4),
(5, 'tiantian', '天天快递', 0, '天天快递', '', '', 1, 10),
(6, 'shunfeng', '顺丰速运', 0, '顺丰', '', '', 1, 1),
(7, 'quanchenkuaidi', '全晨快递', 0, '全晨快递', '', '', 1, 53),
(10, 'huitongkuaidi', '汇通快运', 0, '汇通快运', '', '', 1, 15),
(11, 'yuantong', '圆通速递', 0, '圆通速递', '', '', 1, 3),
(13, 'hjusaexpress', '豪杰快递', 0, '豪杰快递', '', '', 1, 14),
(14, 'goguoguo', '果果快递', 0, '果果快递', '', '', 1, 13),
(15, 'yunda', '韵达快运', 0, '韵达快运', '', '', 1, 2),
(17, 'debangwuliu', '德邦物流', 0, '德邦物流', '', '', 1, 9),
(18, 'ems', 'ems快递', 0, 'ems快递', '', '', 1, 8),
(19, 'quanfengkuaidi', '全峰快递', 0, '全峰快递', '', '', 1, 11),
(20, 'zhaijisong', '宅急送', 0, '宅急送', '', '', 1, 12),
(21, 'guotongkuaidi', '国通快递', 0, '国通快递', '', '', 1, 5),
(22, 'youshuwuliu', '优速物流', 0, '优速物流', '', '', 1, 6),
(23, 'yafengsudi', '亚风速递', 0, '亚风速递', '', '', 1, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wuliu`
--
ALTER TABLE `wuliu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pay_code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wuliu`
--
ALTER TABLE `wuliu`
  MODIFY `id` int(7) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
