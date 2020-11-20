<?php if (!defined('THINK_PATH')) exit();?><html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Insert title here</title>
	<link rel="stylesheet" type="text/css" href="/Public/Admin/style/style.css" />
	<script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
	<style>
		a:link,
		a:visited {
			color: #FFF;
		}

		.mhead h2 {
			position: relative;

		}

		.down_pic {
			background: url('/Public/Admin/images/up_pic.png') 160px no-repeat;
		}

		.up_pic {
			background: url('/Public/Admin/images/down_pic.png') 160px no-repeat;
		}

		.mhead h2 i {
			width: 18px;
			height: 16px;
			position: absolute;
			top: 22px;
			left: 25px;
		}

		.mm1_pic {

			background: url('/Public/Admin/images/mm1_pic.png') no-repeat;
		}

		.mm2_pic {
			background: url('/Public/Admin/images/mm2_pic.png') no-repeat;
		}

		.mm3_pic {
			background: url('/Public/Admin/images/mm3_pic.png') no-repeat;
		}

		.mm4_pic {
			background: url('/Public/Admin/images/mm4_pic.png') no-repeat;
		}
        .a_hover{
            color: red;
        }
	</style>
</head>

<body>
	<div class="left">
		<div class="menu" id="left_menu">
			<div style="padding:16px;background: #343a4c;">
				<img src="/Public/Admin/images/left_logo.png" alt="">
			</div>
			<div class="mhead">
				<h2 class="down_pic">
					<i class="mm1_pic"></i>
					<a onClick="return showmenu('mm1')" href="#">常规管理</a>
				</h2>
			</div>
			<div class="mmiddle" id="mm1" style="display:block;">
				<ul>
					<li>
						<a href="<?php echo U('Index/main');?>" target="main">系统首页</a>
					</li>
					<li>
						<a href="<?php echo U('Article/show');?>" target="main">文档列表</a>
					</li>
					<li>
						<a href="<?php echo U('Article/add');?>" target="main">添加内容</a>
					</li>
				</ul>
			</div>

			<div class="mhead">
				<h2 class="down_pic">
					<i class="mm2_pic"></i>
					<a href="#" onClick="return showmenu('mm2')">会员管理</a>
				</h2>
			</div>
			<div class="mmiddle" id="mm2" style="display:block;">
				<ul>
					<li>
						<a href="<?php echo U('Seller/ushow');?>" target="main">会员列表</a>
					</li>
					<!-- <li>
						<a href="<?php echo U('Admin/Seller/address');?>" target="main">发货地址</a>
					</li> -->
					<li>
						<a href="<?php echo U('Pay/record');?>" target="main">充值记录</a>
					</li>
					<li>
						<a href="<?php echo U('Seller/union');?>" target="main">推广记录</a>
					</li>
					<li>
						<a href="<?php echo U('Pay/alishow');?>" target="main">用户等级</a>
					</li>
					<li>
						<a href="<?php echo U('Seller/score');?>" target="main">消费记录</a>
					</li>
					<li>
						<a href="<?php echo U('Seller/exlist');?>" target="main">空包记录</a>
					</li>
				</ul>
			</div>

			<div class="mhead">
				<h2 class="down_pic">
					<i class="mm3_pic"></i>
					<a href="#" onClick="return showmenu('mm3')">店铺管理</a>
				</h2>
			</div>
			<div class="mmiddle" id="mm3" style="display:block;">
				<ul>
					<li>
						<a href="<?php echo U('Store/shop');?>" target="main">店铺列表</a>
					</li>
					<li>
						<a href="<?php echo U('Store/renew');?>" target="main">续费管理</a>
					</li>
				</ul>
			</div>
			<!-- <div class="mhead">
				<h2 class="down_pic">
					<a href="#" onClick="return showmenu('mm3')">商家管理</a>
				</h2>
			</div>
			<div class="mmiddle" id="mm2" style="display:block;">
				<ul>
					<li>
						<a href="<?php echo U('Admin/Store/sshow');?>" target="main">商家列表</a>
					</li>
					<li>
						<a href="<?php echo U('Admin/Store/address');?>" target="main">发货地址</a>
					</li>
				</ul>
			</div> -->

			<div class="mhead">
				<h2 class="down_pic">
					<i class="mm4_pic"></i>
					<a href="#" onClick="return showmenu('mm4')">系统设置</a>
				</h2>
			</div>
			<div class="mmiddle" id="mm4" style="display:block;">
				<ul>
					<li>
						<a href="<?php echo U('index/tongji');?>" target="main">数据统计</a>
					</li>
					<li>
						<a href="<?php echo U('index/userinfo');?>"  target="main">数据排行</a>
					</li>
					<li>
						<a href="<?php echo U('index/config');?>" target="main">系统参数</a>
					</li>
					<li>
						<a href="<?php echo U('index/fline');?>" target="main">友情链接</a>
					</li>
					<li>
						<a href="<?php echo U('Admin/show');?>" target="main">管理员</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
    <script>
        $("a").each(function() {
            $(this).click(function () {
                $("a").each(function() {
                    $(this).css("color","fff");
                });
                $(this).css("color","#33cccc");
            });
        })
    </script>
</body>

</html>