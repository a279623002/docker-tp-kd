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
	<div class="main">
		<div class="box">
			<div class="bhead">
				<h2>管理员列表</h2>
			</div>
			<div class="bmiddle">
				<div class="bcon">
					<span class="submenu">
						<a href="<?php echo U('Admin/adminAdd');?>">添加管理员</a>
					</span>
					<form id="myform" action="" method="post">
						<table border="0" cellpadding="0" cellspacing="1" width="100%" class="listtable">
							<tr>
								<th width="30">ID</th>
								<th width="150">管理账号</th>
								<th width="250">备注</th>
								<th width="150">更新时间</th>
								<th width="120">操作</th>
								<th></th>
							</tr>
							<?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr>
									<td align="center"><?php echo ($vo["admin_id"]); ?></td>
									<td align="center"><?php echo ($vo["account"]); ?></td>
									<td align="center">
										<?php if(($vo["role"] == 0)): ?>超级管理员
											<?php else: ?>普通管理员<?php endif; ?>
									</td>
									<td align="center"><?php echo ($vo["addtime"]); ?></td>
									<td align="center">
										<?php if(($vo["role"] == 0)): ?><a href="<?php echo U('Admin/Admin/adminAdd/admin_id/'.$vo['admin_id'], '', '');?>">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp; -
											<?php else: ?>				
											<a href="<?php echo U('Admin/Admin/adminAdd/admin_id/'.$vo['admin_id'], '', '');?>">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
											<a href="javascript:del(<?php echo ($vo["admin_id"]); ?>)">删除</a><?php endif; ?>
									</td>
								</tr><?php endforeach; endif; ?>
						</table>
					</form>
					<div class="pageno"> <?php echo ($page); ?> </div>
				</div>
			</div>
			<div class="bfoot">
				<font color="#003366">Copyright:2015 易网包在线操作系统 版权所有</font>
			</div>
		</div>
	</div>

	<script src="/Public/Admin/js/jquery.min.js"></script>
	<script>
	  
	  function del(ID) {
		$.ajax({
		  url: '<?php echo U('Admin/adminDel');?>',
		  type: 'post',
		  dataType: 'json',
		  data: {'admin_id':ID},
		  success: function (data) {
				alert(data.msg)
			  	window.location.reload();
		  }
		})
	  }
	</script>
</body>

</html>