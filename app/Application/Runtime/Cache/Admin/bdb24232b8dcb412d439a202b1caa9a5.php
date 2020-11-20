<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/Public/Admin/style/style.css" />
	<script src="/Public/Admin/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
</head>

<body>
	<div class="top">
		<div class="con">
			<div class="title fl">
				<h1 id="top_title">系统首页</h1>
			</div>
			<!-- <div class="topc fl">
			</div>
			<div class="topr fr">
				<?php echo ($time); ?>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp; 
				当前用户：<?php echo ($account); ?>&nbsp;&nbsp;
				级别：
				<?php if(($role == 0)): ?>超级管理员
					<?php else: ?>普通管理员<?php endif; ?>
				<a style="margin-left:10px;color:#fff;" href="<?php echo U('Admin/Index/main');?>" target="main">后台首页</a>
				<a style="margin-left:10px;color:#fff;" href="/" target="_blank">网站首页</a>
				<a style="margin-left:10px;color:#fff;" href="<?php echo U('Admin/Index/login');?>" target="_top">安全退出</a>
			</div> -->

			<a style="font-size: 16px; color:black;margin: 21px 100px 0 0; background-color: #f7f7f7; background-image: url('/Public/Admin/images/exit_pic.png');background-repeat: no-repeat;background-position: 5px 5px; padding:5px 10px 5px 30px;float:right;"
			 href="<?php echo U('Index/logout');?>" target="_parent">退出</a>
			<span style="font-size: 16px; color:black;margin: 21px 20px 0 0; padding: 5px;float:right;">欢迎回来，<?php echo ($account); ?></span>

			<!-- <img src="/Public/Seller/images/seller_pic.png" style="margin-top: 8px;float:right;" alt=""> -->
			<div class="notice" style="margin-left: 40%;">
				<img src="/Public/Admin/images/notice_pic.png" style="padding: 2px;margin-top:21px;" alt="">
				<marquee direction="left" behavior="scroll" scrollamount="5" scrolldelay="10" align="top" width="20%" hspace="20" vspace="10"
				 onmouseover="this.stop()" onmouseout="this.start()" style="font-size: 14px;margin:21px 30px 0 10px;padding: 2px;color: #666666;">
					<?php echo ($notice); ?>
				</marquee>
			</div>
		</div>
	</div>
</body>

</html>