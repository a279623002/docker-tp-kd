<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/Public/Admin/style/style.css" />
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
</head>

<body>
	<div class="main">
		<div class="box">
			<div class="bhead">
				<h2>添加管理员</h2>
			</div>
			<div class="bmiddle">
				<div class="bcon">
					<form name="myform" id="myform" action="#" method="post">
						<input type="hidden" name="admin_id" value="<?php echo ($detail["admin_id"]); ?>">
						<input type="hidden" name="role" value="<?php echo ($detail["role"]); ?>">
						<table border="0" cellpadding="0" cellspacing="1" width="100%" class="mytable">
							<tbody>
								<tr>
									<th width="150" align="right">管理账号：</th>
									<td>
										<input type="text" name="account" value="<?php echo ($detail["account"]); ?>" style="width:300px;">
									</td>
									<td></td>
								</tr>
								<tr>
									<th width="150" align="right">登录密码：</th>
									<td>
										<input type="password" name="password" style="width:300px;">
									</td>
									<td></td>
								</tr>
								<tr>
									<th></th>
									<td>
										<input type="button" onclick="add()" value="确认提交" class="button">
									</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
			<div class="bfoot">
				<font color="#003366">Copyright:2015 易网包在线操作系统 版权所有</font>
			</div>
		</div>
	</div>
	<script src="/Public/Admin/js/jquery.min.js"></script>
	<script>
  
	  function add() {
		$.ajax({
		  url: '<?php echo U('adminAdd');?>',
		  type: 'post',
		  dataType: 'json',
		  data: $('#myform').serialize(),
		  success: function (data) {
			if (data.state) {
			  alert('添加成功');
			  window.location.href = "<?php echo U('show');?>";
			} else {
			  alert(data.msg)
			}
		  }
		})
	  }
	</script>
</body>

</html>