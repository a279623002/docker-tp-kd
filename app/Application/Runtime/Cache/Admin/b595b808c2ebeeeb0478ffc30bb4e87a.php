<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>网站后台管理系统</title>
</head>
<!-- <frameset rows="55,*" frameborder="NO" border="0" framespacing="0">
	<frame src="<?php echo U('Admin/Index/top');?>" noresize="noresize" frameborder="NO" name="topFrame" scrolling="no" marginwidth="0"
	 marginheight="0" target="main" />
	<frameset cols="210,*" rows="850,*" id="frame">
		<frame src="<?php echo U('Admin/Index/left');?>" name="leftFrame" noresize="noresize" marginwidth="0" marginheight="0" frameborder="0"
		 scrolling="auto" target="main" />
		<frame src="<?php echo U('Admin/Index/main');?>" name="main" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" target="_self"
		/>
	</frameset>
	<noframes>

		<body></body>
	</noframes>

</frameset> -->
	<frameset cols="210,*" framespacing="0" frameborder="0">
		<frame src="<?php echo U('Index/left');?>" name="leftFrame" id="leftFrame"  noresize="noresize" marginwidth="0" marginheight="0" frameborder="0"
		 scrolling="auto" target="main" />
		<frameset rows="80,*">

			<frame src="<?php echo U('Index/top');?>" noresize="noresize" frameborder="NO" name="topFrame" scrolling="no" marginwidth="0"
			 marginheight="0" target="main" />
			<frame src="<?php echo U('Index/main');?>" name="main" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" target="_self"
			/>

		</frameset>

		<noframes>

			<body>您的浏览器无法处理框架！</body>
		</noframes>


</html>