<?php
//==================================================================================================
//
//《支付宝免签约即时到账辅助》付款接口提交
//本页面用于将数据库中已记录的订单发送到支付宝网站进行付款
//
//本接口示例代码演示了接口工作流程，您可以修改示例代码进行集成，也可以根据开发文档自行编写接口代码
//如果您不清楚如何集成接口到网站，也可以联系QQ：40386277付费集成
//
//==================================================================================================
//获取付款金额
require(dirname(__FILE__).'/Config.php');
$Amount = isset($_GET['Amount'])?$_GET["Amount"]:0;
//获取付款说明（建议使用订单号，提交到支付宝前先将订单信息记录到数据库）
$Title = isset($_GET['Title'])?$_GET["Title"]:'';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>支付宝在线支付</title>
</head>
<body>
<form action="/alidirectV2/" method="post" name="alidirect" accept-charset="gb2312" onsubmit="document.charset='gb2312'">
<input type="hidden" name="optEmail" value="<?php echo $alidirect_account;?>">
<input type="hidden" name="payAmount" value="<?php echo $Amount;?>">
<input type="hidden" name="title" value="<?php echo $Title;?>" />
</form>
<script>document.alidirect.submit();</script>
</body>
</html>