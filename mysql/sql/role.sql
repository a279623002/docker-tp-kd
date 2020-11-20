SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- 表的结构 `kd_admin`
--

CREATE TABLE IF NOT EXISTS `kd_admin` (
  `admin_id` int(11) unsigned NOT NULL,
  `account` varchar(32) NOT NULL COMMENT '账号',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `login_ip` varchar(64) NOT NULL COMMENT '登陆IP',
  `role` tinyint(3) NOT NULL DEFAULT '0' COMMENT '权限0超级管理1普通管理',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态1正常2异常'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员';

--
-- 转存表中的数据 `kd_admin`
--

INSERT INTO `kd_admin` (`admin_id`, `account`, `password`, `login_time`, `login_ip`, `role`, `addtime`, `status`) VALUES
(1, 'zero', '0fa4387d5705ff4c27b4dccf80262542', 1559633808, '121.8.154.178', 0, 0, 1),
(3, 'hao', 'd12ad75e5fc00bbaa2df37b3709092c1', 0, '183.16.191.101', 1, 1538216518, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kd_article`
--

CREATE TABLE IF NOT EXISTS `kd_article` (
  `article_id` int(11) NOT NULL,
  `title` varchar(55) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `cate` tinyint(3) NOT NULL COMMENT '分类1运营技巧2推广赚钱3加盟代理',
  `add_time` int(11) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='文章';

--
-- 转存表中的数据 `kd_article`
--

INSERT INTO `kd_article` (`article_id`, `title`, `content`, `cate`, `add_time`, `status`) VALUES
(39, '易网包正式上线的通知', '<p style="text-align: center;"><span style="font-size: 24px;">易网包</span></p><p><span style="font-size: 16px;">易网包淘宝智能发货系统，既定于11月1日正式上线运营！</span></p>', 1, 2018, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kd_flink`
--

CREATE TABLE IF NOT EXISTS `kd_flink` (
  `flink_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL COMMENT '链接名称1',
  `link` varchar(255) NOT NULL COMMENT '链接地址',
  `pic` varchar(255) NOT NULL COMMENT 'LOGO图片',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '链接排序',
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='友情链接';

--
-- 转存表中的数据 `kd_flink`
--

INSERT INTO `kd_flink` (`flink_id`, `name`, `link`, `pic`, `sort`, `addtime`, `status`) VALUES
(1, '百度', 'https://www.baidu.com', '5bb87b7386736.jpg', 1, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kd_kuaidi`
--

CREATE TABLE IF NOT EXISTS `kd_kuaidi` (
  `id` int(11) unsigned NOT NULL,
  `k_name` varchar(20) DEFAULT NULL,
  `k_code` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '每单金额',
  `remark` varchar(50) DEFAULT NULL COMMENT '说明，备注'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `kd_kuaidi`
--

INSERT INTO `kd_kuaidi` (`id`, `k_name`, `k_code`, `price`, `remark`) VALUES
(1, '圆通', 'YTO', '2.90', '最新圆通');

-- --------------------------------------------------------

--
-- 表的结构 `kd_kuaidi_level`
--

CREATE TABLE IF NOT EXISTS `kd_kuaidi_level` (
  `express_id` int(5) DEFAULT NULL COMMENT '快递id',
  `level_id` int(5) DEFAULT NULL COMMENT '等级Id',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '空包费用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `kd_kuaidi_level`
--

INSERT INTO `kd_kuaidi_level` (`express_id`, `level_id`, `price`) VALUES
(1, 1, '3.00'),
(1, 2, '2.80'),
(1, 3, '2.60'),
(1, 4, '2.40'),
(1, 5, '2.30');

-- --------------------------------------------------------

--
-- 表的结构 `kd_print_program`
--

CREATE TABLE IF NOT EXISTS `kd_print_program` (
  `program_code` varchar(30) NOT NULL COMMENT 'keyID',
  `program_name` varchar(50) DEFAULT NULL COMMENT '快递助手名称',
  `program_pic` varchar(255) DEFAULT NULL COMMENT '助手图片链接',
  `buy_url` varchar(255) DEFAULT NULL COMMENT '订购链接',
  `auth_url` varchar(255) DEFAULT NULL COMMENT '订购链接'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快递助手列表';

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller`
--

CREATE TABLE IF NOT EXISTS `kd_seller` (
  `seller_id` int(11) unsigned NOT NULL,
  `account` varchar(32) NOT NULL COMMENT '账号',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `QQ` varchar(20) NOT NULL COMMENT 'QQ',
  `wechat` varchar(32) DEFAULT NULL COMMENT '微信',
  `alipay` varchar(32) DEFAULT NULL COMMENT '支付宝',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `last_login_ip` varchar(64) DEFAULT NULL COMMENT '登陆IP',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '推广用户ID',
  `tui_money` decimal(10,2) DEFAULT '0.10' COMMENT '推广佣金，默认1毛钱',
  `level_id` tinyint(3) NOT NULL DEFAULT '1' COMMENT '用户等级1普2黄3钻4VIP5特',
  `left_points` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '剩余点数',
  `spread` tinyint(3) NOT NULL DEFAULT '1' COMMENT '推广权限1无2有',
  `bonus` decimal(10,2) DEFAULT '0.00' COMMENT '奖励金额',
  `seller_type` int(1) DEFAULT NULL COMMENT '1-淘宝，2-天猫，3-1688，4-京东，5-微商；6-其它',
  `seller_nick` varchar(32) DEFAULT NULL COMMENT '淘宝账号,天猫账号，京东账号',
  `check_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `left_growth` int(11) DEFAULT NULL COMMENT '当前成长值',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态1-待审核，2-审核通过，3-禁用'
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员表';

--
-- 转存表中的数据 `kd_seller`
--

INSERT INTO `kd_seller` (`seller_id`, `account`, `password`, `money`, `mobile`, `email`, `QQ`, `wechat`, `alipay`, `add_time`, `last_login_time`, `last_login_ip`, `parent_id`, `tui_money`, `level_id`, `left_points`, `spread`, `bonus`, `seller_type`, `seller_nick`, `check_time`, `left_growth`, `status`) VALUES
(44, '13417050399', '85122516131f2e9755939d3ce8864002', '0.00', NULL, NULL, '15146', NULL, NULL, 1541147088, 1541147097, '120.238.54.238', 0, '0.10', 1, '0.00', 1, '0.00', NULL, NULL, NULL, NULL, 1),
(45, '18819445160', '35d92a3e5d481f2a99fe0f01e61068e9', '0.00', NULL, NULL, '746436624', NULL, NULL, 1543988806, 1543988812, '223.73.143.181', 0, '0.10', 1, '0.00', 1, '0.00', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_address`
--

CREATE TABLE IF NOT EXISTS `kd_seller_address` (
  `id` int(11) unsigned NOT NULL,
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家会员ID',
  `address_type` int(1) DEFAULT NULL COMMENT '1-淘宝，2-天猫，3-1688，4-京东，5-微商；6-其它',
  `seller_nick` varchar(100) DEFAULT NULL COMMENT '店铺名称',
  `address_no` varchar(30) DEFAULT NULL COMMENT '通淘宝接口或者京东接口获取的地址编号',
  `send_name` varchar(50) DEFAULT NULL COMMENT '发货人',
  `send_mobile` varchar(50) DEFAULT NULL COMMENT '发货人手机（看看是否要支持座机）',
  `send_provice` varchar(50) DEFAULT NULL COMMENT '发货人省份：浙江省',
  `send_city` varchar(50) DEFAULT NULL COMMENT '发货人城市：杭州市',
  `send_district` varchar(50) DEFAULT NULL COMMENT '发货人区：大湾区',
  `send_town` varchar(50) DEFAULT NULL COMMENT '发货人街道/镇：余杭镇',
  `send_address` varchar(500) DEFAULT NULL COMMENT '发货人详细地址：学苑大道208号 国通大厦203室王小姐',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `is_default` int(1) DEFAULT '0' COMMENT '0-非默认；1-默认发货地址',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1正常；2-删除，不显示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='发货地址';

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_finance`
--

CREATE TABLE IF NOT EXISTS `kd_seller_finance` (
  `f_id` int(11) unsigned NOT NULL,
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `money_prev` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原有金额',
  `money_next` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现有金额',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '点数，1点对应1分钱',
  `change_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `change_desc` varchar(100) NOT NULL COMMENT '操作说明：备注成功，扣除10个点;快递单号分配成功，扣除230点',
  `change_type` tinyint(3) NOT NULL DEFAULT '99' COMMENT '类型：1充值；2消费；3-佣金奖励；99其他',
  `task_id` int(11) DEFAULT '0' COMMENT '对应的任务ID,充值或者其他可以为0',
  `left_points` int(11) DEFAULT '0' COMMENT '剩余点数，回写到seller表',
  `left_growth` int(11) DEFAULT '0' COMMENT '剩余成长值，充值的时候加成长值，points消费的时候不扣成长值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='账户记录';

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_level`
--

CREATE TABLE IF NOT EXISTS `kd_seller_level` (
  `level_id` int(11) NOT NULL COMMENT '用户等级1普2黄3钻4VIP5特',
  `level_name` varchar(55) NOT NULL COMMENT 'level等级名',
  `growth_from` int(11) NOT NULL DEFAULT '0' COMMENT '成才值开始',
  `growth_end` int(11) NOT NULL DEFAULT '0' COMMENT '成长值结束',
  `reduction` decimal(10,2) DEFAULT NULL COMMENT '充值-优惠比例',
  `is_show` tinyint(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户等级积分';

--
-- 转存表中的数据 `kd_seller_level`
--

INSERT INTO `kd_seller_level` (`level_id`, `level_name`, `growth_from`, `growth_end`, `reduction`, `is_show`) VALUES
(1, '普通会员', 0, 0, '0.00', 1),
(2, '黄金会员', 1000, 0, '0.00', 1),
(3, '钻石会员', 2000, 0, '0.00', 1),
(4, 'VIP会员', 5000, 0, '0.00', 1),
(5, '特级会员', 1000000, 0, '0.00', 2);

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_log`
--

CREATE TABLE IF NOT EXISTS `kd_seller_log` (
  `log_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL COMMENT '商家id',
  `log_type` int(3) DEFAULT '0' COMMENT '日志类型；1-注册日志；2-审核日志；3-登录日志；4-关闭充值订单；5-修改店铺信息；6-充值',
  `log_desc` varchar(100) DEFAULT NULL COMMENT '日志描述：注册申请；后台审核通过；',
  `add_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_recharge`
--

CREATE TABLE IF NOT EXISTS `kd_seller_recharge` (
  `charge_id` int(11) unsigned NOT NULL,
  `seller_id` int(11) unsigned DEFAULT '0',
  `charge_type` tinyint(1) unsigned DEFAULT '0',
  `charge_money` decimal(10,2) unsigned DEFAULT '0.00',
  `charge_descript` varchar(50) DEFAULT NULL,
  `charge_number` varchar(50) DEFAULT NULL,
  `charge_account` varchar(50) DEFAULT NULL,
  `charge_create` int(11) DEFAULT '0' COMMENT '充值创建时间',
  `back_true` tinyint(1) unsigned DEFAULT '1',
  `back_tradeno` varchar(50) DEFAULT NULL,
  `back_money` decimal(10,2) unsigned DEFAULT '0.00',
  `back_title` varchar(50) DEFAULT NULL,
  `back_memo` varchar(100) DEFAULT NULL,
  `back_alipay_account` varchar(100) DEFAULT NULL,
  `back_tenpay_account` varchar(100) DEFAULT NULL,
  `back_gateway` varchar(50) DEFAULT NULL,
  `back_sign` varchar(50) DEFAULT NULL,
  `back_paytime` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_recharge_back`
--

CREATE TABLE IF NOT EXISTS `kd_seller_recharge_back` (
  `back_id` int(11) unsigned NOT NULL,
  `seller_id` int(11) unsigned DEFAULT '0',
  `back_tradeno` varchar(50) DEFAULT NULL,
  `back_title` varchar(50) DEFAULT NULL,
  `back_money` decimal(10,2) DEFAULT '0.00',
  `back_paytime` varchar(50) DEFAULT '0',
  `back_status` tinyint(1) unsigned DEFAULT '1',
  `back_create` int(11) DEFAULT '0',
  `back_update` int(11) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_spread_log`
--

CREATE TABLE IF NOT EXISTS `kd_seller_spread_log` (
  `id` int(11) unsigned NOT NULL,
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `top_seller_id` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- 表的结构 `kd_seller_store`
--

CREATE TABLE IF NOT EXISTS `kd_seller_store` (
  `store_id` int(11) NOT NULL COMMENT '店铺Id',
  `seller_id` int(11) DEFAULT NULL COMMENT '商家ID',
  `manager` varchar(55) NOT NULL COMMENT '掌柜号',
  `property` tinyint(3) NOT NULL DEFAULT '1' COMMENT '店铺性质1个人2其他',
  `store_nick` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺昵称',
  `store_type` int(2) NOT NULL DEFAULT '1' COMMENT '所属平台1-淘宝，2-天猫，3-1688，4-京东，5-微商；6-其它',
  `name` varchar(55) NOT NULL COMMENT '发货人姓名',
  `mobile` varchar(255) NOT NULL COMMENT '发货人电话',
  `province` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `district` varchar(20) DEFAULT NULL COMMENT '区',
  `address` varchar(100) NOT NULL COMMENT '详细地址',
  `image` varchar(255) NOT NULL COMMENT '截图',
  `msg` varchar(100) DEFAULT NULL COMMENT '拒绝通过原因',
  `assistant` tinyint(3) NOT NULL DEFAULT '1' COMMENT '助手授权1未授权2已授权',
  `vip_deadline` int(11) DEFAULT NULL COMMENT '商家VIP到期时间',
  `print_deadline` int(11) DEFAULT NULL COMMENT '快递助手过期时间',
  `re_mark` tinyint(3) NOT NULL DEFAULT '1' COMMENT '标记1标记2不标记',
  `tb_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '淘宝，天猫标旗：卖家备注旗帜 红、黄、绿、蓝、紫 分别对应 1、2、3、4、5',
  `tb_remark` varchar(200) DEFAULT '' COMMENT '淘宝，天猫备注',
  `goods_weight` decimal(5,2) DEFAULT '0.50' COMMENT '货物重量，当商品里面没有重量，以这里为准。',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '店铺更新时间',
  `check_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `check_user_id` int(11) DEFAULT NULL COMMENT '审核管理员用户id',
  `print_program_code` varchar(20) DEFAULT NULL COMMENT '对接的快递助手',
  `default_express_code_1` varchar(20) DEFAULT NULL COMMENT '默认快递公司1,设置好以后，空包自动程序会按优先顺序匹配',
  `default_express_code_2` varchar(20) DEFAULT NULL COMMENT '默认快递公司2，为空不处理',
  `default_express_code_3` varchar(20) DEFAULT NULL COMMENT '默认快递公司3',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-申请中；2-审核通过；3-审核拒绝'
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺';

--
-- 转存表中的数据 `kd_seller_store`
--

INSERT INTO `kd_seller_store` (`store_id`, `seller_id`, `manager`, `property`, `store_nick`, `store_type`, `name`, `mobile`, `province`, `city`, `district`, `address`, `image`, `msg`, `assistant`, `vip_deadline`, `print_deadline`, `re_mark`, `tb_flag`, `tb_remark`, `goods_weight`, `add_time`, `update_time`, `check_time`, `check_user_id`, `print_program_code`, `default_express_code_1`, `default_express_code_2`, `default_express_code_3`, `status`) VALUES
(35, 44, 'adasd ', 1, '哈哈哈', 1, '东莞上文网络科技有限公司', '13417050399', '天津', '天津市', '和平区', '广东汕头', '5bdc09efa703b.jpg', NULL, 1, NULL, NULL, 1, 0, '', '0.50', 2018, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kd_system`
--

CREATE TABLE IF NOT EXISTS `kd_system` (
  `system_id` int(11) NOT NULL,
  `website` varchar(55) NOT NULL COMMENT '网站地址',
  `abstract` varchar(55) NOT NULL COMMENT '网站关键词',
  `describe` varchar(55) NOT NULL COMMENT '网站描述',
  `register_money` decimal(10,2) NOT NULL COMMENT '注册送金额',
  `first_level` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级推广会员奖励',
  `second_level` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级推广会员奖励',
  `online_qq` int(11) NOT NULL COMMENT '在线客服QQ',
  `commerce_qq` int(11) NOT NULL COMMENT '商务合作QQ',
  `index_notice` varchar(55) NOT NULL COMMENT '会员首页公告',
  `alipay_key` varchar(55) NOT NULL COMMENT '支付宝授权密钥',
  `alipay_account` varchar(55) NOT NULL COMMENT '支付宝收款账号',
  `register_limit` tinyint(3) NOT NULL COMMENT '注册限制1是0否',
  `info` varchar(55) NOT NULL COMMENT '分销联盟说明',
  `count_code` varchar(255) NOT NULL COMMENT '统计代码',
  `copyright` varchar(255) NOT NULL COMMENT '版权信息',
  `name` varchar(55) NOT NULL COMMENT '网站名称'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统参数';

--
-- 转存表中的数据 `kd_system`
--

INSERT INTO `kd_system` (`system_id`, `website`, `abstract`, `describe`, `register_money`, `first_level`, `second_level`, `online_qq`, `commerce_qq`, `index_notice`, `alipay_key`, `alipay_account`, `register_limit`, `info`, `count_code`, `copyright`, `name`) VALUES
(1, 'http://www.ywb999.com', '易网包', '易网包', '0.00', '5.00', '0.00', 4844952, 4844952, '易网包正式上线！联系客服开通邀请权限，邀请商家加入平台赚取高额佣金!', '52fgdsgsdf324trteter645', '无需填写233', 1, '122', 'tj33', '<span style="font-family:Microsoft YaHei;"><span style="font-family:Microsoft YaHei;"><span style="font-family:Microsoft YaHei;"><span style="font-family:Microsoft YaHei;"><span style="font-family:Microsoft YaHei;"><span style="font-family:Microsoft YaHei', '易网包');

-- --------------------------------------------------------

--
-- 表的结构 `kd_task`
--

CREATE TABLE IF NOT EXISTS `kd_task` (
  `task_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `order_no` varchar(50) NOT NULL COMMENT '订单编号',
  `express` tinyint(3) NOT NULL DEFAULT '1' COMMENT '快递公司1圆通',
  `uplog_id` int(11) DEFAULT '0' COMMENT '导入ID',
  `unit_price` float(11,0) NOT NULL COMMENT '价格',
  `order_type` int(2) NOT NULL DEFAULT '2' COMMENT '1-淘宝,天猫；2-1688；3-京东；4-手动录入，只需要一个快递单号而已',
  `buyer_nick` varchar(50) DEFAULT NULL COMMENT '买家昵称',
  `send_name` varchar(50) DEFAULT NULL COMMENT '发货人姓名',
  `send_mobile` varchar(50) DEFAULT NULL COMMENT '发货人手机（看看是否要支持座机）',
  `send_provice` varchar(50) DEFAULT NULL COMMENT '发货人省份：浙江省',
  `send_city` varchar(50) DEFAULT NULL COMMENT '发货人城市：杭州市',
  `send_district` varchar(50) DEFAULT NULL COMMENT '发货人区：大湾区',
  `send_town` varchar(50) DEFAULT NULL COMMENT '发货人街道/镇：余杭镇',
  `send_address` varchar(500) DEFAULT NULL COMMENT '发货人详细地址：学苑大道208号 国通大厦203室王小姐',
  `receiver_name` varchar(50) DEFAULT NULL COMMENT '收货人姓名',
  `receiver_mobile` varchar(50) DEFAULT NULL COMMENT '收货人手机（看看是否要支持座机）',
  `receiver_provice` varchar(50) DEFAULT NULL COMMENT '收货人省份：浙江省',
  `receiver_city` varchar(50) DEFAULT NULL COMMENT '收货人城市：杭州市',
  `receiver_district` varchar(50) DEFAULT NULL COMMENT '收货人区：大湾区',
  `receiver_town` varchar(50) DEFAULT NULL COMMENT '收货人街道/镇：余杭镇',
  `receiver_address` varchar(500) DEFAULT NULL COMMENT '收货人详细地址：学苑大道208号 国通大厦203室王小姐',
  `goods` varchar(500) NOT NULL DEFAULT '' COMMENT '商品名称描述',
  `goods_nums` int(5) DEFAULT NULL COMMENT '商品数量',
  `goods_weight` decimal(10,2) NOT NULL COMMENT '单个商品重量',
  `add_time` int(11) DEFAULT NULL COMMENT '添加任务时间',
  `remark_state` tinyint(3) NOT NULL DEFAULT '1' COMMENT '备注状态；1-未备注；2-备注成功；3-备注失败',
  `remark_error_msg` varchar(100) DEFAULT NULL COMMENT '打标错误信息',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1-导入；2-订单同步成功；3-订单同步失败',
  `update_time` int(11) DEFAULT NULL COMMENT '订单最后更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='任务';

-- --------------------------------------------------------

--
-- 表的结构 `kd_task_kongbao`
--

CREATE TABLE IF NOT EXISTS `kd_task_kongbao` (
  `k_id` int(11) NOT NULL COMMENT '自增ID',
  `task_id` int(11) DEFAULT NULL COMMENT '任务ID',
  `seller_id` int(11) DEFAULT NULL COMMENT '商家会员ID',
  `store_id` int(11) DEFAULT NULL COMMENT '店铺Id',
  `k_status` tinyint(2) DEFAULT '1' COMMENT '1-待分配快递单；2-已分配快递单；3-分配快递单失败；4-回收快递单',
  `k_price` decimal(10,2) DEFAULT NULL COMMENT '每个空包的成本价格;',
  `k_payment` decimal(10,2) DEFAULT NULL COMMENT '商家支付的费用;',
  `tb_status` tinyint(2) DEFAULT '1' COMMENT '1-待发货；2-已发货；3-发货失败；',
  `tb_failure` varchar(500) DEFAULT '' COMMENT '淘宝发货失败原因；',
  `kd_failure` varchar(500) DEFAULT NULL COMMENT '空包产生错误',
  `order_no` varchar(50) DEFAULT NULL COMMENT '淘宝订单号',
  `express_id` tinyint(2) DEFAULT NULL COMMENT '快递公司ID',
  `waybill_no` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `kbtime` int(11) DEFAULT NULL COMMENT '分配快递单号时间',
  `tbtime` int(11) DEFAULT NULL COMMENT '淘宝发货时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='空包任务列表，和空包记录';

-- --------------------------------------------------------

--
-- 表的结构 `kd_task_logs`
--

CREATE TABLE IF NOT EXISTS `kd_task_logs` (
  `log_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL COMMENT '任务ID',
  `log_type` int(3) NOT NULL COMMENT '日志类型；1-数据同步；2-备注、旗帜；3-分配空包；4-分配空包失败；5-分配空包成功；',
  `log_content` varchar(100) NOT NULL COMMENT '日志描述如：分配圆通快递单号：201545646872110',
  `log_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='任务日志表';

-- --------------------------------------------------------

--
-- 表的结构 `kd_union_log`
--

CREATE TABLE IF NOT EXISTS `kd_union_log` (
  `log_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '推广员ID',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '奖励金',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='推广记录';

-- --------------------------------------------------------

--
-- 表的结构 `kd_uplog`
--

CREATE TABLE IF NOT EXISTS `kd_uplog` (
  `uplog_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL COMMENT '商家ID',
  `express` tinyint(3) NOT NULL COMMENT '发货公司',
  `shop` varchar(55) NOT NULL COMMENT '发货店铺',
  `order_time` int(11) NOT NULL COMMENT '下单时间',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL COMMENT '订单总数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='导入订单';

-- --------------------------------------------------------

--
-- 表的结构 `kd_user_renew`
--

CREATE TABLE IF NOT EXISTS `kd_user_renew` (
  `renew_id` int(11) NOT NULL,
  `month` int(11) NOT NULL COMMENT '月份',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `kd_user_renew`
--

INSERT INTO `kd_user_renew` (`renew_id`, `month`, `price`, `status`) VALUES
(6, 1, '200.00', 1),
(7, 12, '2000.00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kd_admin`
--
ALTER TABLE `kd_admin`
  ADD PRIMARY KEY (`admin_id`) USING BTREE;

--
-- Indexes for table `kd_article`
--
ALTER TABLE `kd_article`
  ADD PRIMARY KEY (`article_id`) USING BTREE;

--
-- Indexes for table `kd_flink`
--
ALTER TABLE `kd_flink`
  ADD PRIMARY KEY (`flink_id`) USING BTREE;

--
-- Indexes for table `kd_kuaidi`
--
ALTER TABLE `kd_kuaidi`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `kd_print_program`
--
ALTER TABLE `kd_print_program`
  ADD PRIMARY KEY (`program_code`) USING BTREE;

--
-- Indexes for table `kd_seller`
--
ALTER TABLE `kd_seller`
  ADD PRIMARY KEY (`seller_id`) USING BTREE;

--
-- Indexes for table `kd_seller_address`
--
ALTER TABLE `kd_seller_address`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `kd_seller_finance`
--
ALTER TABLE `kd_seller_finance`
  ADD PRIMARY KEY (`f_id`) USING BTREE;

--
-- Indexes for table `kd_seller_level`
--
ALTER TABLE `kd_seller_level`
  ADD PRIMARY KEY (`level_id`) USING BTREE;

--
-- Indexes for table `kd_seller_log`
--
ALTER TABLE `kd_seller_log`
  ADD PRIMARY KEY (`log_id`) USING BTREE;

--
-- Indexes for table `kd_seller_recharge`
--
ALTER TABLE `kd_seller_recharge`
  ADD PRIMARY KEY (`charge_id`) USING BTREE;

--
-- Indexes for table `kd_seller_recharge_back`
--
ALTER TABLE `kd_seller_recharge_back`
  ADD PRIMARY KEY (`back_id`) USING BTREE;

--
-- Indexes for table `kd_seller_spread_log`
--
ALTER TABLE `kd_seller_spread_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `kd_seller_store`
--
ALTER TABLE `kd_seller_store`
  ADD PRIMARY KEY (`store_id`) USING BTREE,
  ADD UNIQUE KEY `store_nick` (`store_nick`) USING BTREE;

--
-- Indexes for table `kd_system`
--
ALTER TABLE `kd_system`
  ADD PRIMARY KEY (`system_id`) USING BTREE;

--
-- Indexes for table `kd_task`
--
ALTER TABLE `kd_task`
  ADD PRIMARY KEY (`task_id`) USING BTREE,
  ADD UNIQUE KEY `order_no` (`order_no`) USING BTREE;

--
-- Indexes for table `kd_task_kongbao`
--
ALTER TABLE `kd_task_kongbao`
  ADD PRIMARY KEY (`k_id`) USING BTREE;

--
-- Indexes for table `kd_task_logs`
--
ALTER TABLE `kd_task_logs`
  ADD PRIMARY KEY (`log_id`) USING BTREE;

--
-- Indexes for table `kd_union_log`
--
ALTER TABLE `kd_union_log`
  ADD PRIMARY KEY (`log_id`) USING BTREE;

--
-- Indexes for table `kd_uplog`
--
ALTER TABLE `kd_uplog`
  ADD PRIMARY KEY (`uplog_id`) USING BTREE;

--
-- Indexes for table `kd_user_renew`
--
ALTER TABLE `kd_user_renew`
  ADD PRIMARY KEY (`renew_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kd_admin`
--
ALTER TABLE `kd_admin`
  MODIFY `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `kd_article`
--
ALTER TABLE `kd_article`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `kd_flink`
--
ALTER TABLE `kd_flink`
  MODIFY `flink_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kd_kuaidi`
--
ALTER TABLE `kd_kuaidi`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kd_seller`
--
ALTER TABLE `kd_seller`
  MODIFY `seller_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `kd_seller_address`
--
ALTER TABLE `kd_seller_address`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_seller_finance`
--
ALTER TABLE `kd_seller_finance`
  MODIFY `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_seller_level`
--
ALTER TABLE `kd_seller_level`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户等级1普2黄3钻4VIP5特',AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `kd_seller_log`
--
ALTER TABLE `kd_seller_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_seller_recharge`
--
ALTER TABLE `kd_seller_recharge`
  MODIFY `charge_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_seller_recharge_back`
--
ALTER TABLE `kd_seller_recharge_back`
  MODIFY `back_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_seller_spread_log`
--
ALTER TABLE `kd_seller_spread_log`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kd_seller_store`
--
ALTER TABLE `kd_seller_store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺Id',AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `kd_system`
--
ALTER TABLE `kd_system`
  MODIFY `system_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kd_task`
--
ALTER TABLE `kd_task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_task_kongbao`
--
ALTER TABLE `kd_task_kongbao`
  MODIFY `k_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID';
--
-- AUTO_INCREMENT for table `kd_task_logs`
--
ALTER TABLE `kd_task_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_union_log`
--
ALTER TABLE `kd_union_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_uplog`
--
ALTER TABLE `kd_uplog`
  MODIFY `uplog_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kd_user_renew`
--
ALTER TABLE `kd_user_renew`
  MODIFY `renew_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
