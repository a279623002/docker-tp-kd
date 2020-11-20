<?php
//==================================================================================================
//
//《支付宝免签约即时到账辅助》回调通知接收
//本页面用于接收《支付宝免签约即时到账辅助》发来的收款信息，验证信息并修改数据库中记录
//
//本接口示例代码演示了接口工作流程，您可以修改示例代码进行集成，也可以根据开发文档自行编写接口代码
//如果您不清楚如何集成接口到网站，也可以联系QQ：40386277付费集成
//
//==================================================================================================
//数据库连接
$db=mysql_connect('数据库服务器','数据库用户','数据库密码') or die('无法连接到数据库');
mysql_select_db('数据库名', $db);
mysql_query('set names utf8;');

require(dirname(__FILE__).'/Config.php');
$tradeNo = isset($_POST['tradeNo'])?$_POST['tradeNo']:'';
$Money = isset($_POST['Money'])?$_POST['Money']:0;
$title = isset($_POST['title'])?$_POST['title']:'';
$memo = isset($_POST['memo'])?$_POST['memo']:'';
$alipay_account = isset($_POST['alipay_account'])?$_POST['alipay_account']:'';
$Gateway = isset($_POST['Gateway'])?$_POST['Gateway']:'';
$Sign = isset($_POST['Sign'])?$_POST['Sign']:'';
if(strtoupper(md5($alidirect_pid . $alidirect_key . $tradeNo . $Money . $title . $memo)) == strtoupper($Sign))
{
//MD5签名验证通过后，判断订单是否存在，判断订单是否已处理，判断订单金额是否与通知中的金额一致
//全部验证通过后即可做到账处理，并修改订单状态为已处理

//以下为示例，您可以删除示例代码编写您自己的代码
//==================================================================================================
	$result = mysql_fetch_array(mysql_query("select * from 订单表 where 订单号=".$title));//读取支付前保存的订单信息
	if($result)
	{
		if($result['订单金额字段']!=$Money)//实际支付金额不匹配
		{
			exit('Fail');
		}
		else
		{
			if($result['订单状态']==0)//判断订单未处理过
			{
				$username = $result['username'];//从之前保存的订单中读取付款用户名
				mysql_query("update 订单表 set 支付宝交易号字段=$tradeNo,订单状态 = 1 where 订单号=".$title);//记录支付宝交易号，并修改交易状态为成功（status=1）
				mysql_query("update 用户表 set 余额字段 = 余额字段 + $Money where 用户名字段='$username'");//以收到的$Money为准给用户增加金额，如果是购物车订单结算可不需要这一步
			}
			exit('Success');//支付成功
		}
		
	}
	else
	{
		exit('IncorrectOrder');//订单不存在
	}
	
//==================================================================================================
}
else
{
	exit('Fail');//Sign签名验证失败
}
?>