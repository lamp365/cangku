-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-14 10:47:59
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
-- 表的结构 `baohuo`
--

CREATE TABLE IF NOT EXISTS `baohuo` (
  `id` int(10) unsigned NOT NULL,
  `huo_id` int(10) NOT NULL COMMENT '货源ID',
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `cm_id` int(10) NOT NULL COMMENT '尺码id',
  `address_id` int(10) DEFAULT '0' COMMENT '发货地址',
  `pic` varchar(255) NOT NULL COMMENT '主图',
  `pic2` text COMMENT '附加图',
  `num` int(10) DEFAULT '1' COMMENT '数量',
  `shop_id` int(10) DEFAULT '0' COMMENT '店铺id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '组id',
  `chang_id` int(10) NOT NULL COMMENT '厂家id',
  `jin_price` decimal(10,2) DEFAULT '0.00' COMMENT '进价',
  `da_price` decimal(10,2) DEFAULT '0.00' COMMENT '打包费',
  `mai_price` decimal(10,2) DEFAULT '0.00' COMMENT '卖出价',
  `customer` varchar(200) DEFAULT '' COMMENT '客户名字',
  `c_mobile` char(11) DEFAULT '' COMMENT '客户电话',
  `c_address` text COMMENT '客户地址',
  `yu_order` varchar(255) DEFAULT '' COMMENT '预约单号',
  `send_order` varchar(255) DEFAULT '' COMMENT '发货单号',
  `chang_order` varchar(255) DEFAULT '' COMMENT '厂家单号',
  `chang_order_code` varchar(30) DEFAULT '' COMMENT '厂家单号对应物流代号',
  `yu_order_code` varchar(30) DEFAULT '' COMMENT '预约单号对应物流代号',
  `back_order_code` varchar(30) DEFAULT '' COMMENT '退回单号物流代号',
  `yun_info` varchar(255) DEFAULT '' COMMENT '运营备注',
  `can_info` varchar(255) DEFAULT '' COMMENT '仓库备注',
  `is_jie` tinyint(2) DEFAULT '0' COMMENT '0无 1借用其他组',
  `back_order` varchar(255) DEFAULT '' COMMENT '退回单号',
  `back_reason` varchar(255) DEFAULT '' COMMENT '退回理由',
  `mude` tinyint(2) NOT NULL COMMENT '1为发货 2为备货',
  `diaohuo` tinyint(2) NOT NULL COMMENT '1库存 2调货 3缺货 4调货完毕',
  `order_state` tinyint(2) DEFAULT '0' COMMENT '0申请中 1处理中 2调货完毕 3发货完毕 -1关闭',
  `after_state` tinyint(2) DEFAULT '0' COMMENT '0暂无 1换货中 2退货中 -1换货完毕 -2退货完毕',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `f_date` int(10) DEFAULT '0' COMMENT '调货完毕时间',
  `s_date` int(10) DEFAULT '0' COMMENT '发货完毕时间'
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='报货表';

--
-- 转存表中的数据 `baohuo`
--

INSERT INTO `baohuo` (`id`, `huo_id`, `cat_id1`, `cat_id2`, `cm_id`, `address_id`, `pic`, `pic2`, `num`, `shop_id`, `uid`, `gid`, `chang_id`, `jin_price`, `da_price`, `mai_price`, `customer`, `c_mobile`, `c_address`, `yu_order`, `send_order`, `chang_order`, `chang_order_code`, `yu_order_code`, `back_order_code`, `yun_info`, `can_info`, `is_jie`, `back_order`, `back_reason`, `mude`, `diaohuo`, `order_state`, `after_state`, `c_date`, `f_date`, `s_date`) VALUES
(1, 1, 1, 3, 3, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '300.00', '50.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子..', '', 0, '', '', 1, 2, 0, 0, 1531407537, 0, 0),
(2, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(3, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(4, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(5, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(6, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(7, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(8, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(9, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(10, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, -1, 0, 1531389178, 0, 0),
(11, 1, 1, 3, 2, 0, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 1, 1, 1, 1, 2, '0.00', '0.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', '', '', '', '', '', '', '多送一个袋子', '', 0, '', '', 1, 2, 0, 0, 1531389178, 0, 0),
(12, 1, 1, 3, 5, 1, '/upload/huoyuan/201806/5b321901e6c9c.png', '/upload/baohuo/201807/5b4723b9ce624.png', 2, 1, 1, 1, 2, '300.00', '50.00', '678.00', '刘大明', '188507387', '尽快哈萨克假大空金山毒霸目不识丁', 'V00191980886', '', '', '', 'yuantong', '', '多送一个袋子..', '', 0, '', '', 1, 2, 0, 0, 1531407858, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baohuo`
--
ALTER TABLE `baohuo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `huo_cm_id` (`huo_id`,`cm_id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `customer` (`customer`),
  ADD KEY `mude` (`mude`),
  ADD KEY `diaohuo` (`diaohuo`),
  ADD KEY `order_state` (`order_state`),
  ADD KEY `after_state` (`after_state`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baohuo`
--
ALTER TABLE `baohuo`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
