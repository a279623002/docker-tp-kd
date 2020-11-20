<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="/Public/Admin/style/login.css" />
<script src="/Public/Admin/js/jquery-1.7.2.js"></script>
</head>
<script>
function checkmyform(){
	if(document.myform.account.value==''){
		alert('请填写用户名');
		document.myform.account.focus();
		return false;
	}
	
	if(document.myform.password.value==''){
		alert('请填写密码');
		document.myform.password.focus();
		return false;
	}
	$.ajax({
		url: '<?php echo U('Index/login');?>',
		type: 'post',
		dataType: 'json',
		data: $("#loginForm").serialize(),
		success: function (data) {
          if(data.state) {
         	 window.location.href = "<?php echo U('Index/index');?>";
          } else {
         	 alert(data.msg);
          }
		}
 });
}
</script>
<body>
<div class="login">
	<div class="login_top">后台登录</div>
	<div class="login_con">
		<form name="myform" action="" method="post" id="loginForm">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="right">用户名：</td>
				<td><input name="account" type="text"  class="inp" /></td>
			</tr>
			<tr>
				<td align="right">密码：</td>
				<td><input name="password" type="password"  class="inp" /></td>
			</tr>

			<tr>
				<td></td>
				<td>
					<a href="#" onClick="return checkmyform()"><img src="/Public/Admin/images/loginbtn.jpg"/></a>
				</td>
			</tr>
		</table>
		</form>
	</div>
	<div class="login_bottom"></div>
</div>


</body>
</html>