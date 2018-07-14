-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-14 10:47:28
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
-- 表的结构 `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `send_name` varchar(30) DEFAULT '' COMMENT '用于发货名',
  `send_mobile` char(11) DEFAULT '' COMMENT '用于发货用的手机',
  `send_address` varchar(255) DEFAULT '' COMMENT '发货人地址',
  `is_default` tinyint(2) DEFAULT '0' COMMENT '0为非默认 1为默认地址',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='发货地址表一个用户对应多条';

--
-- 转存表中的数据 `address`
--

INSERT INTO `address` (`id`, `uid`, `send_name`, `send_mobile`, `send_address`, `is_default`, `c_date`) VALUES
(1, 1, '刘建凡', '18850737047', '奥术大师风神股份钢结构海港城', 0, 0),
(2, 1, '刘建凡', '23423453213', '发个啥认为今年部分东方闪电v', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `backchanjia`
--

CREATE TABLE IF NOT EXISTS `backchanjia` (
  `id` int(10) unsigned NOT NULL,
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `pic` varchar(255) NOT NULL COMMENT '图片一张',
  `chang_id` int(10) NOT NULL COMMENT '厂家id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '组id',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `info` text COMMENT '备注'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='退回厂家';

--
-- 转存表中的数据 `backchanjia`
--

INSERT INTO `backchanjia` (`id`, `cat_id1`, `cat_id2`, `pic`, `chang_id`, `uid`, `gid`, `c_date`, `info`) VALUES
(1, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(2, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(3, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(4, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(5, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(6, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL),
(7, 1, 3, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 1, 1, 1530010158, NULL);

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

-- --------------------------------------------------------

--
-- 表的结构 `bill`
--

CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(10) unsigned NOT NULL,
  `bao_id` int(10) DEFAULT '0' COMMENT '报货ID',
  `shop_id` int(10) DEFAULT '0' COMMENT '店铺ID',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '组id',
  `pic` varchar(255) NOT NULL COMMENT '主图',
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `cm_id` int(10) NOT NULL COMMENT '尺码id',
  `jin_price` decimal(10,2) DEFAULT '0.00' COMMENT '进价合计',
  `da_price` decimal(10,2) DEFAULT '0.00' COMMENT '打包费',
  `mai_price` decimal(10,2) DEFAULT '0.00' COMMENT '卖出价格合计',
  `shua_price` decimal(10,2) DEFAULT '0.00' COMMENT '刷单价格',
  `shen_price` decimal(10,2) DEFAULT '0.00' COMMENT '剩下余额',
  `kind` tinyint(2) DEFAULT '1' COMMENT '1系统账单 2后台账单',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `x_date` int(10) DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(2) DEFAULT '0' COMMENT '1为调货 2为发货 3为刷单',
  `info` text COMMENT '备注',
  `is_check` tinyint(2) DEFAULT '0' COMMENT '0为财务没审核 1为审核了'
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='账单';

--
-- 转存表中的数据 `bill`
--

INSERT INTO `bill` (`id`, `bao_id`, `shop_id`, `uid`, `gid`, `pic`, `cat_id1`, `cat_id2`, `cm_id`, `jin_price`, `da_price`, `mai_price`, `shua_price`, `shen_price`, `kind`, `c_date`, `x_date`, `state`, `info`, `is_check`) VALUES
(1, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '50.00', '1200.00', '0.00', '680.00', 1, 1530009868, 0, 1, NULL, 0),
(2, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '50.00', '1200.00', '0.00', '680.00', 1, 1530009868, 0, 1, NULL, 0),
(3, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '42.00', '1200.00', '26.00', '680.00', 1, 1530009868, 0, 1, NULL, 0),
(4, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '50.00', '1100.00', '0.00', '680.00', 1, 1530009868, 0, 2, NULL, 0),
(5, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '50.00', '1200.00', '0.00', '680.00', 1, 1530009868, 0, 2, NULL, 0),
(6, 1, 1, 1, 1, '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, 2, '320.00', '50.00', '1200.00', '0.00', '680.00', 1, 1530009868, 1530179742, 1, NULL, 1),
(7, 0, 1, 1, 1, '/Public/no_photo.png', 0, 0, 0, '0.00', '0.00', '0.00', '23.00', '0.00', 2, 1530541154, 0, 3, '4rtert', 1),
(8, 0, 3, 1, 1, '/Public/no_photo.png', 0, 0, 0, '0.00', '0.00', '0.00', '456.00', '0.00', 2, 1530542141, 0, 3, 'shuade ', 1),
(9, 0, 3, 1, 1, '/Public/no_photo.png', 0, 0, 0, '0.00', '0.00', '0.00', '34.00', '0.00', 2, 1530542317, 0, 3, '还不错的', 1),
(10, 0, 2, 1, 1, '/Public/no_photo.png', 0, 0, 0, '0.00', '0.00', '0.00', '76.00', '0.00', 2, 1530542420, 0, 3, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `borrow`
--

CREATE TABLE IF NOT EXISTS `borrow` (
  `id` int(10) unsigned NOT NULL,
  `b_uid` int(10) NOT NULL COMMENT '借的用户id',
  `b_mobile` char(11) NOT NULL COMMENT '借的人电话',
  `uid` int(10) NOT NULL COMMENT '借谁的东西',
  `gid` int(10) NOT NULL COMMENT '借哪个组的东西',
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `pic` varchar(255) NOT NULL COMMENT '图片一张',
  `chang_id` int(10) NOT NULL COMMENT '厂家id',
  `states` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1为借出，2为已还',
  `info` text COMMENT '备注',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `h_date` int(10) DEFAULT '0' COMMENT '大概还回时间'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='借出记录表';

--
-- 转存表中的数据 `borrow`
--

INSERT INTO `borrow` (`id`, `b_uid`, `b_mobile`, `uid`, `gid`, `cat_id1`, `cat_id2`, `pic`, `chang_id`, `states`, `info`, `c_date`, `h_date`) VALUES
(1, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314),
(2, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314),
(3, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314),
(4, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314),
(5, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314),
(6, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 2, NULL, 1530786314, 1530799314),
(7, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 2, NULL, 1530786314, 1530799314),
(8, 2, '1008611', 1, 1, 0, 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg', 0, 1, NULL, 1530786314, 1530799314);

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL COMMENT '父id',
  `cat_name` varchar(30) NOT NULL COMMENT '名字',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为删除'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='分类表 两级分类';

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `pid`, `cat_name`, `is_delete`) VALUES
(1, 0, 'GUCCI', 0),
(2, 0, '阿玛尼', 0),
(3, 1, '皮带', 0),
(4, 1, '衣服', 0),
(5, 2, '皮带', 0),
(6, 2, '衣服', 0);

-- --------------------------------------------------------

--
-- 表的结构 `changjia`
--

CREATE TABLE IF NOT EXISTS `changjia` (
  `id` int(10) unsigned NOT NULL,
  `ch_name` varchar(30) NOT NULL COMMENT '厂家名字',
  `cname` varchar(30) DEFAULT '' COMMENT '厂家别名',
  `info` varchar(255) DEFAULT '' COMMENT '备注',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为删除'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='厂家表';

--
-- 转存表中的数据 `changjia`
--

INSERT INTO `changjia` (`id`, `ch_name`, `cname`, `info`, `is_delete`) VALUES
(1, 'weixin1567', '金耀家', '专做鞋子', 0),
(2, '587128', '档口1807', '专做衣服', 0),
(3, '234234', '杭州商贸', '专做皮带', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_size`
--

CREATE TABLE IF NOT EXISTS `cm_size` (
  `id` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `cm_name` varchar(30) NOT NULL COMMENT '名字',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为删除'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='尺码大小二级分类';

--
-- 转存表中的数据 `cm_size`
--

INSERT INTO `cm_size` (`id`, `pid`, `cm_name`, `is_delete`) VALUES
(1, 0, '皮带', 0),
(2, 1, '80码', 0),
(3, 1, '85码', 0),
(4, 0, '衣服', 0),
(5, 1, '90码', 0),
(6, 4, 'S码', 0),
(7, 4, 'M码', 0),
(8, 4, 'L码', 0),
(9, 4, 'XL码', 0),
(10, 4, 'XXL码', 0),
(11, 4, '3XL码', 0),
(12, 4, '4XL码', 0),
(13, 1, '95码', 0),
(14, 1, '100码', 0),
(15, 1, '105码', 0),
(16, 1, '110码', 0);

-- --------------------------------------------------------

--
-- 表的结构 `dangeruser`
--

CREATE TABLE IF NOT EXISTS `dangeruser` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) NOT NULL COMMENT '用户id',
  `d_name` varchar(60) DEFAULT '' COMMENT '黑名用户',
  `d_mobile` char(11) DEFAULT '' COMMENT '黑名用户手机号',
  `d_address` varchar(255) DEFAULT '' COMMENT '黑名用户地址',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `pic` varchar(255) DEFAULT '' COMMENT '图片凭证',
  `info` text COMMENT '备注'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='黑名榜单';

--
-- 转存表中的数据 `dangeruser`
--

INSERT INTO `dangeruser` (`id`, `uid`, `d_name`, `d_mobile`, `d_address`, `c_date`, `pic`, `info`) VALUES
(1, 1, '宝宝不哭lo', '188232', '阿萨所发生的', 0, 'https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c05f18.jpeg,https://hinrc.oss-cn-shanghai.aliyuncs.com/201702/2017020512355896ab8c27675.jpeg', '恶意卖家'),
(2, 1, '宝宝不哭', '188232', '阿萨所发生的', 0, '', '恶意卖家'),
(3, 1, '宝宝不哭2', '188232', '阿萨所发生的', 0, '', '恶意卖家45');

-- --------------------------------------------------------

--
-- 表的结构 `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `employeeID` int(11) NOT NULL,
  `employee_num` varchar(18) DEFAULT NULL COMMENT '员工编号10010001',
  `name` varchar(30) DEFAULT NULL COMMENT '姓名',
  `Sex` tinyint(1) DEFAULT NULL COMMENT '性别1为男2为女',
  `Email` varchar(64) DEFAULT NULL COMMENT '邮箱',
  `Mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `pic` varchar(80) DEFAULT NULL,
  `DepartmentNum` varchar(4) DEFAULT NULL COMMENT '部门编号',
  `ParentID` int(11) DEFAULT NULL COMMENT '上级ID',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `random` varchar(6) DEFAULT NULL COMMENT '密码随机数',
  `register_time` datetime DEFAULT NULL,
  `Note` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) DEFAULT '0' COMMENT '0正常1锁定',
  `position` varchar(80) DEFAULT NULL COMMENT '职务',
  `isPriority` tinyint(1) DEFAULT '1' COMMENT '是否为部门负责人2为否1为是',
  `del` tinyint(1) DEFAULT '0',
  `Sort` int(11) DEFAULT NULL,
  `ModifyTime` datetime DEFAULT NULL,
  `OperatorID` int(11) DEFAULT NULL,
  `DutyID` int(11) DEFAULT NULL COMMENT '权限用户组id'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='员工信息表';

--
-- 转存表中的数据 `employee`
--

INSERT INTO `employee` (`employeeID`, `employee_num`, `name`, `Sex`, `Email`, `Mobile`, `pic`, `DepartmentNum`, `ParentID`, `password`, `random`, `register_time`, `Note`, `status`, `position`, `isPriority`, `del`, `Sort`, `ModifyTime`, `OperatorID`, `DutyID`) VALUES
(7, 'admin', '建凡', NULL, NULL, '1008611', NULL, NULL, NULL, '317258ae413c87b365e5f32ad8755937', '76269', '2018-05-13 19:55:46', NULL, 0, NULL, 1, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `huoyuan`
--

CREATE TABLE IF NOT EXISTS `huoyuan` (
  `id` int(10) unsigned NOT NULL,
  `h_name` varchar(200) NOT NULL COMMENT '名字',
  `pic` varchar(255) NOT NULL COMMENT '主图非空',
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `jin_price` decimal(10,2) DEFAULT '0.00' COMMENT '进价',
  `da_price` decimal(10,2) DEFAULT '0.00' COMMENT '打包费',
  `info` varchar(255) DEFAULT '' COMMENT '备注',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为删除',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='货源表';

--
-- 转存表中的数据 `huoyuan`
--

INSERT INTO `huoyuan` (`id`, `h_name`, `pic`, `cat_id1`, `cat_id2`, `jin_price`, `da_price`, `info`, `is_delete`, `c_date`) VALUES
(1, '黑色印花皮带', '/upload/huoyuan/201806/5b321901e6c9c.png', 1, 3, '300.00', '50.00', '还不错的', 0, 1530009868),
(2, '经典皮带流行', '/upload/huoyuan/201806/5b321a093d6ac.png', 1, 3, '320.00', '50.00', '值得推荐', 0, 1530010158),
(3, '尼龙皮带', '/upload/huoyuan/201806/5b3345e33c1ff.png', 1, 3, '340.00', '54.00', '', 0, 1530086901),
(4, '三色短袖', '/upload/huoyuan/201806/5b3347cf36cca.png', 2, 6, '120.00', '40.00', '', 0, 1530087377),
(5, '黑色纯棉', '/upload/huoyuan/201806/5b3348096f782.jpg', 2, 6, '88.00', '40.00', '', 0, 1530087449),
(6, '永和大王新款皮带', '/upload/huoyuan/201807/5b470591dbb84.png', 1, 3, '330.00', '45.00', '还好吧', 0, 1531381162);

-- --------------------------------------------------------

--
-- 表的结构 `kucun`
--

CREATE TABLE IF NOT EXISTS `kucun` (
  `id` int(10) unsigned NOT NULL,
  `huo_id` int(10) NOT NULL COMMENT '货源ID',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '组id',
  `pic` varchar(255) NOT NULL COMMENT '主图非空',
  `cat_id1` int(10) NOT NULL COMMENT '分类1',
  `cat_id2` int(10) NOT NULL COMMENT '分类2',
  `cm_id` int(10) NOT NULL COMMENT '尺码id',
  `num` int(10) DEFAULT '0' COMMENT '库存数量',
  `jin_price` decimal(10,2) DEFAULT '0.00' COMMENT '进价',
  `da_price` decimal(10,2) DEFAULT '0.00' COMMENT '打包费',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `x_date` int(10) DEFAULT '0' COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='库存表';

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `pic` varchar(255) NOT NULL COMMENT '图片一张',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='新闻表';

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `pic`, `c_date`) VALUES
(1, '网站开通成功', '<p>不错哦，刚刚测试一下还行的</p>', '/Public/Admin/img/news.jpg', 1530711685),
(5, '还好吧', '&lt;p&gt;真的还好呀。。&lt;/p&gt;', '/upload/news/201807/5b3cd56ab2269.png', 1530713458),
(6, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(3, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(4, '你好word', '&lt;p&gt;你好wod&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/upload/news/201807/5b3cd43691006.png&quot; style=&quot;&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/upload/news/201807/5b3cd43e2c48b.png&quot; style=&quot;&quot;&gt;&lt;br&gt;&lt;/p&gt;', '/Public/Admin/img/news.jpg', 1530713152),
(7, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(8, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(9, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(10, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(11, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739),
(12, '这是2个文字', '&lt;p&gt;这是一个文章&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/upload/news/201807/5b3cd29160c94.png&quot; style=&quot;&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;/upload/news/201807/5b3cd29bb2c8c.png&quot; style=&quot;&quot;&gt;&lt;br&gt;&lt;/p&gt;', '/Public/Admin/img/news.jpg', 1530712739),
(13, '这是一个文字', '&amp;lt;p&amp;gt;这是一个文章&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29160c94.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;&#47;p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;img src=&amp;quot;&#47;upload&#47;news&#47;201807&#47;5b3cd29bb2c8c.png&amp;quot; style=&amp;quot;&amp;quot;&amp;gt;&amp;lt;br&amp;gt;&amp;lt;&#47;p&amp;gt;', '/Public/Admin/img/news.jpg', 1530712739);

-- --------------------------------------------------------

--
-- 表的结构 `recharge`
--

CREATE TABLE IF NOT EXISTS `recharge` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '组id',
  `chon_price` decimal(10,2) DEFAULT '0.00' COMMENT '充值金额',
  `shen_price` decimal(10,2) DEFAULT '0.00' COMMENT '剩下余额',
  `pic` varchar(255) DEFAULT '' COMMENT '图片凭证',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `state` tinyint(2) DEFAULT '0' COMMENT '0为待审核 1审核通过 -1审核失败',
  `info` text COMMENT '备注'
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='充值表';

--
-- 转存表中的数据 `recharge`
--

INSERT INTO `recharge` (`id`, `uid`, `gid`, `chon_price`, `shen_price`, `pic`, `c_date`, `state`, `info`) VALUES
(1, 1, 1, '10.00', '10.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(17, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(16, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 1, NULL),
(4, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 1, NULL),
(5, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 1, NULL),
(6, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 1, NULL),
(7, 1, 1, '17.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(8, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 1, NULL),
(9, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(10, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(11, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(12, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(13, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(14, 1, 1, '10.00', '10.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(15, 1, 1, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(18, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(19, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(20, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(21, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(22, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL),
(23, 3, 3, '15.00', '25.00', 'http://odozak4lg.bkt.clouddn.com/201611291414583d1cbdcbd65.jpg,http://odozak4lg.bkt.clouddn.com/20161125102058379fec319d7.jpg', 1530022716, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `siteconfig`
--

CREATE TABLE IF NOT EXISTS `siteconfig` (
  `site_name` varchar(40) DEFAULT '' COMMENT '网站名字',
  `site_pic` varchar(255) DEFAULT '' COMMENT '网站LOGO',
  `beian` varchar(60) DEFAULT '' COMMENT '备案号',
  `keyword` varchar(255) DEFAULT '' COMMENT '网站关键字',
  `description` varchar(255) NOT NULL DEFAULT ' ' COMMENT '网站简介',
  `mobile` varchar(255) DEFAULT '' COMMENT '客服电话',
  `qq` varchar(255) DEFAULT '' COMMENT '客服QQ',
  `about` text COMMENT '关于我们',
  `id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `siteconfig`
--

INSERT INTO `siteconfig` (`site_name`, `site_pic`, `beian`, `keyword`, `description`, `mobile`, `qq`, `about`, `id`) VALUES
('34542324', '/upload/default/201807/5b3ccbd4382f0.png', '23423423', '', ' qweq', '', '', '&lt;p&gt;你们&lt;/p&gt;&lt;p&gt;暗示你打算&lt;/p&gt;&lt;p&gt;97hasgad4&lt;/p&gt;', 1);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL COMMENT '组id',
  `mobile` char(11) NOT NULL COMMENT '手机号 用于登录和联系',
  `password` char(64) NOT NULL COMMENT '密码',
  `user_name` varchar(30) DEFAULT '' COMMENT '用户名字',
  `level` tinyint(2) DEFAULT '0' COMMENT '0为组员 1为组长',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `is_forbid` tinyint(2) DEFAULT '0' COMMENT '0正常 1禁止登录'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户';

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `gid`, `mobile`, `password`, `user_name`, `level`, `c_date`, `is_forbid`) VALUES
(1, 1, '10086100861', '14e1b600b1fd579f47433b88e8d85291', 'kevin', 1, 0, 0),
(2, 1, '13423432424', '14e1b600b1fd579f47433b88e8d85291', '小月', 0, 1529921518, 0),
(3, 3, '189203', '14e1b600b1fd579f47433b88e8d85291', 'lanyuel', 1, 1530086113, 0);

-- --------------------------------------------------------

--
-- 表的结构 `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(10) unsigned NOT NULL,
  `group_name` varchar(30) DEFAULT '' COMMENT '组名',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '小组资金',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '1删除 0不删除 组下有成员就软删'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组';

--
-- 转存表中的数据 `user_group`
--

INSERT INTO `user_group` (`id`, `group_name`, `money`, `c_date`, `is_delete`) VALUES
(1, '霹雳组', '30.00', 1529919231, 0),
(3, '火蓝组', '0.00', 1530009584, 0);

-- --------------------------------------------------------

--
-- 表的结构 `user_shop`
--

CREATE TABLE IF NOT EXISTS `user_shop` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `gid` int(10) unsigned NOT NULL COMMENT '组id',
  `shop_name` varchar(200) DEFAULT '' COMMENT '店铺名',
  `shop_zg` varchar(200) DEFAULT '' COMMENT '店铺掌柜',
  `c_date` int(10) DEFAULT '0' COMMENT '创建时间',
  `info` varchar(255) DEFAULT '' COMMENT '备注信息',
  `is_forbid` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为禁止使用',
  `is_delete` tinyint(2) DEFAULT '0' COMMENT '0为正常 1为删除'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户拥有的店铺表';

--
-- 转存表中的数据 `user_shop`
--

INSERT INTO `user_shop` (`id`, `uid`, `gid`, `shop_name`, `shop_zg`, `c_date`, `info`, `is_forbid`, `is_delete`) VALUES
(1, 1, 1, '花花试驾', 'tb7638', 1530009868, '还不错吧3453435                    ', 0, 0),
(2, 1, 1, '花花试驾3', 'tb7er638', 1530009868, '还不错吧       nchkjsd             ', 0, 0),
(3, 1, 1, '花花试驾3', 'tb7er638', 0, '还不错吧', 0, 0);

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
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `is_default` (`is_default`);

--
-- Indexes for table `backchanjia`
--
ALTER TABLE `backchanjia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `chang_id` (`chang_id`);

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
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `bao_id` (`bao_id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `state` (`state`),
  ADD KEY `check` (`is_check`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `b_uid` (`b_uid`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `chang_id` (`chang_id`),
  ADD KEY `states` (`states`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `is_delete` (`is_delete`);

--
-- Indexes for table `changjia`
--
ALTER TABLE `changjia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_delete` (`is_delete`);

--
-- Indexes for table `cm_size`
--
ALTER TABLE `cm_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `dangeruser`
--
ALTER TABLE `dangeruser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `d_name` (`d_name`),
  ADD KEY `d_mobile` (`d_mobile`),
  ADD KEY `d_address` (`d_address`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employeeID`);

--
-- Indexes for table `huoyuan`
--
ALTER TABLE `huoyuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `is_delete` (`is_delete`);

--
-- Indexes for table `kucun`
--
ALTER TABLE `kucun`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id1` (`cat_id1`),
  ADD KEY `cat_id2` (`cat_id2`),
  ADD KEY `huo_cm_id` (`huo_id`,`cm_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recharge`
--
ALTER TABLE `recharge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `state` (`state`);

--
-- Indexes for table `siteconfig`
--
ALTER TABLE `siteconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gid` (`gid`),
  ADD KEY `is_forbid` (`is_forbid`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_shop`
--
ALTER TABLE `user_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `is_forbid` (`is_forbid`),
  ADD KEY `is_delete` (`is_delete`);

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
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `backchanjia`
--
ALTER TABLE `backchanjia`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `baohuo`
--
ALTER TABLE `baohuo`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `changjia`
--
ALTER TABLE `changjia`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cm_size`
--
ALTER TABLE `cm_size`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `dangeruser`
--
ALTER TABLE `dangeruser`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `huoyuan`
--
ALTER TABLE `huoyuan`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `kucun`
--
ALTER TABLE `kucun`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `recharge`
--
ALTER TABLE `recharge`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `siteconfig`
--
ALTER TABLE `siteconfig`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_shop`
--
ALTER TABLE `user_shop`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wuliu`
--
ALTER TABLE `wuliu`
  MODIFY `id` int(7) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
