<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<!-- Mirrored from laike.oaishua.com/Login/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 17 Jul 2018 06:13:31 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>用户登录</title>
  <meta name="keywords" content="易网包会员登录" />
  <meta name="description" content="易网包会员登录" />
  <link rel="stylesheet" href="/Public/Home/css/login.css" />
  <link rel="stylesheet" href="/Public/Home/css/amazeui.min.css" />
  <script src="/Public/Home/js/jquery-1.7.2.js"></script>
  <link rel="stylesheet" type="text/css" href="/Public/Home/css/base.css">
  <link rel="stylesheet" type="text/css" href="/Public/Home/css/shao.css">
  <!-- bootstrap -->
  <link href="/Public/Home/css/bootstrap.css" rel="stylesheet" />
  <link href="/Public/Home/css/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
  <!-- libraries -->
  <link href="/Public/Home/css/font-awesome.css" type="text/css" rel="stylesheet" />
  <!-- global styles -->
  <link rel="stylesheet" type="text/css" href="/Public/Home/css/layout.css">
  <!-- this page specific styles -->
  <link rel="stylesheet" href="/Public/Home/css/m.index.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="/Public/Home/css/amazeui.datatables.css" />
  <!--am-g-->
  <style>
    body {
      background: #f5f5f5;
    }

    .am-table .am-active {
      font-weight: bold;
      color: #32a0ee
    }

    .notification-dropdown {
      width: 10%;
      text-align: center;
    }

    .notification-dropdown a {
      color: #FFF !important;
      font-size: 16px !important;
    }

    a {
      text-decoration: none !important;
    }

    .notification-dropdown a:hover {
      background: #1fa799 !important;
    }

    body::-webkit-scrollbar {
      display: none
    }
  </style>
</head>

<body>
  <!-- top -->
  <div style="float:left;width:100%;height:80px;position: relative;z-index: 9999;background: #FFF;">
    <div style="width:75%;margin:0 auto;">
      <a href="/">
        <img src="/Public/Seller/images/logo.png" style="float: left; margin-top: 15px;" alt="">
      </a>

      <div style="float:right;">

        <?php if(($seller_id != 0)): ?><a href="<?php echo U('Home/Seller/index');?>">
            <img src="/Public/Seller/images/seller_pic.png" alt="">
          </a>
          <span style="font-size: 16px; color:black; line-height: 80px; padding: 5px;">欢迎回来，<?php echo ($seller["account"]); ?></span>
          <a style="font-size: 16px; color:black; line-height: 80px; background: #f7f7f7; padding:5px 10px 5px 10px;" href="<?php echo U('Home/Seller/logout');?>">退出</a>
          <?php else: ?>
          <a style="font-size: 20px; color:black; line-height: 80px;" href="<?php echo U('Home/Seller/login');?>">会员登录</a> |
          <a style="font-size: 20px; color:black; line-height: 80px;" href="<?php echo U('Home/Seller/register');?>">免费注册</a><?php endif; ?>
      </div>
      <marquee direction="left" behavior="scroll" scrollamount="5" scrolldelay="10" align="top" bgcolor="#eee" width="30%" hspace="20"
        vspace="10" onmouseover="this.stop()" onmouseout="this.start()" style="font-size: 1.6rem;padding: 2px;border-radius: 10px;margin: 2% 0 0 10%;">
        <?php echo ($notice); ?>
      </marquee>
    </div>
  </div>
  <!-- end top -->

  <!-- navbar -->
  <header class="navbar" role="banner" style="background:#21ae9f;margin-bottom: 0 !important;">
    <ul class="nav navbar-nav pull-left" style="padding-left: 12%; width: 100%;">
      <li class="notification-dropdown ">
        <a href="/">首页</a>
      </li>
      <li class="notification-dropdown ">
                <a href="<?php echo U('Home/Seller/index');?>">会员中心</a>
            </li>
      <li class="notification-dropdown ">
        <a href="<?php echo U('Home/Order/pay');?>">充值 </a>
      </li>
      <li class="notification-dropdown ">
        <a href="<?php echo U('Home/Express/newadd');?>">发布任务</a>
      </li>
      <li class="notification-dropdown ">
        <a href="<?php echo U('Home/Express/explist');?>">任务记录</a>
      </li>
      <li class="notification-dropdown ">
        <a href="<?php echo U('Home/Seller/union');?>">推广赚钱</a>
      </li>

    </ul>
  </header>
  <!-- end navbar -->
  <div class="banner-show" id="js_ban_content">
    <div class="cell bns-01">
      <div class="con">
      </div>
    </div>
    <div class="cell bns-02" style="display:none;">
      <div class="con">
      </div>
    </div>
    <div class="cell bns-03" style="display:none;">
      <div class="con">
      </div>
    </div>
  </div>
  <div class="banner-control" id="js_ban_button_box">
    <a href="javascript:;" class="left">左</a>
    <a href="javascript:;" class="right">右</a>
  </div>

  <script src="/Public/Home/js/login.js"></script>

  <div class="container">
    <div class="login-box">

      <div class="login-slogan">用户登录<a href="<?php echo U('Home/Seller/register');?>">免费注册</a></div>
      <div class="login-form" id="js-form-mobile">
        <form name="myform" action="" id="loginForm" method="post">
          <div class="cell">
            <input type="tel" name="account" id="account" placeholder="用户名/手机号" />
          </div>
          <div class="cell">
            <input type="password" name="password" id="password" placeholder="密码" />
          </div>
          <div class="bottom">
            <a id="js-mobile_btn" href="javascript:login();" class="button btn-green">登录</a>
          </div>
          <br>
        </form>
      </div>
      <input type="checkbox" style="margin-right: 5px;"><span style="color:#999;">记住用户名  </span>
    </div>
  </div>
  <footer class="am-footer am-footer-default" style="background: #2e2e2e;">
    <div class="am-footer-miscs " style="color: #FFF;">
      <p>
        <a href="<?php echo U('Home/Article/detail');?>" style="color: #FFF;">运营技巧</a>&nbsp;&nbsp;|
        <a href="<?php echo U('Home/Article/detail');?>" style="color: #FFF;">帮助说明</a>&nbsp;&nbsp;| 友情链接: &nbsp;&nbsp;
        <?php if(is_array($flink)): foreach($flink as $k=>$vo): ?><a href="<?php echo ($vo["link"]); ?>" class="" style="color: #FFF;">
            <!-- <img src="/Public/../Uploads/flink/<?php echo ($vo['pic']); ?>" style="width: 20px; height: 20px;" alt="" srcset=""> -->
            <?php echo ($vo["name"]); ?></a>&nbsp;&nbsp;<?php endforeach; endif; ?>
      </p>
      <br />
      <p>由
        <a href="/" title="易网包" target="_blank" style="color: #FFF;"> 易网包 </a>提供技术支持</p>
      <p>CopyRight©2018 YW Inc.</p>
      <p> </p>
    </div>
  </footer>
  <script type="text/javascript">

    function OnlineOver() {
      document.getElementById("aFloatTools_Show").style.display = "none";
      document.getElementById("divFloatToolsView").style.display = "block";
      document.getElementById("aFloatTools_Hide").style.display = "block";
      document.getElementById("floatTools").style.width = "190px";
    }
    function OnlineOut() {
      document.getElementById("aFloatTools_Show").style.display = "block";
      document.getElementById("aFloatTools_Hide").style.display = "none";
      document.getElementById("divFloatToolsView").style.display = "none";
      document.getElementById("floatTools").style.width = "36px";
    }
    if (typeof (HTMLElement) != "undefined")    //给firefox定义contains()方法，ie下不起作用
    {
      HTMLElement.prototype.contains = function (obj) {
        while (obj != null && typeof (obj.tagName) != "undefind") { //通过循环对比来判断是不是obj的父元素
          if (obj == this) return true;
          obj = obj.parentNode;
        }
        return false;
      };
    }
    function hideMsgBox(theEvent) { //theEvent用来传入事件，Firefox的方式
      if (theEvent) {
        var browser = navigator.sellerAgent; //取得浏览器属性
        if (browser.indexOf("Firefox") > 0) { //如果是Firefox
          if (document.getElementById('divFloatToolsView').contains(theEvent.relatedTarget)) { //如果是子元素
            return; //结束函式
          }
        }
        if (browser.indexOf("MSIE") > 0) { //如果是IE
          if (document.getElementById('divFloatToolsView').contains(event.toElement)) { //如果是子元素
            return; //结束函式
          }
        }
      }
      /*要执行的操作*/
      document.getElementById("aFloatTools_Show").style.display = "block";
      document.getElementById("aFloatTools_Hide").style.display = "none";
      document.getElementById("divFloatToolsView").style.display = "none";
      document.getElementById("floatTools").style.width = "36px";
    }
  </script>
  <script type="text/javascript">
    function login() {
      $.ajax({
        url: '<?php echo U('Seller/login');?>',
				type: 'post',
        dataType: 'json',
        data: $("#loginForm").serialize(),
        success: function (data) {
          if (data.state) {
            window.location.href = "<?php echo U('Seller/index');?>";
          } else {
            alert(data.msg);
          }
        }
      });
    }
  </script>

  <style type="text/css">
    .rides-cs {
      font-size: 12px;
      background: #29a7e2;
      position: fixed;
      top: 250px;
      right: 0px;
      _position: absolute;
      z-index: 1500;
      border-radius: 6px 0px 0 6px;
    }

    .rides-cs a {
      color: #00A0E9;
    }

    .rides-cs a:hover {
      color: #ff8100;
      text-decoration: none;
    }

    .rides-cs .floatL {
      width: 36px;
      float: left;
      position: relative;
      z-index: 1;
      margin-top: 21px;
      height: 181px;
    }

    .rides-cs .floatL a {
      font-size: 0;
      text-indent: -999em;
      display: block;
    }

    .rides-cs .floatR {
      width: 130px;
      float: left;
      padding: 5px;
      overflow: hidden;
    }

    .rides-cs .floatR .cn {
      background: #F7F7F7;
      border-radius: 6px;
      margin-top: 4px;
    }

    .rides-cs .cn .titZx {
      font-size: 14px;
      color: #333;
      font-weight: 600;
      line-height: 24px;
      padding: 5px;
      text-align: center;
    }

    .rides-cs .cn ul {
      padding: 0px;
    }

    .rides-cs .cn ul li {
      line-height: 38px;
      height: 38px;
      border-bottom: solid 1px #E6E4E4;
      overflow: hidden;
      text-align: center;
    }

    .rides-cs .cn ul li span {
      color: #777;
    }

    .rides-cs .cn ul li a {
      color: #777;
    }

    .rides-cs .cn ul li img {
      vertical-align: middle;
      display: inline
    }

    .rides-cs .btnOpen,
    .rides-cs .btnCtn {
      position: relative;
      z-index: 9;
      top: 25px;
      left: 0;
      background-image: url(../Public/Home/img/qqopen.png);
      background-repeat: no-repeat;
      display: block;
      height: 146px;
      padding: 8px;
    }

    .rides-cs .btnOpen {
      background-position: 0 0;
    }

    .rides-cs .btnCtn {
      background-position: -37px 0;
    }

    .rides-cs ul li.top {
      border-bottom: solid #ACE5F9 1px;
    }

    .rides-cs ul li.bot {
      border-bottom: none;
    }

    #rightTools {
      height: 235px;
      width: 70px;
      position: fixed;
      right: 0px;
      top: 200px;
      background: #FFF;
      z-index: 9999;
    }

    #rightTools ul {
      width: 70px;
      padding: 0;
      margin: 0;
    }

    #rightTools ul li {
      width: 70px;
      height: 80px;
      list-style-type: none;
      text-align: center;
      border-bottom: 1px solid #EEE;
      margin-bottom: 5px;
      cursor: pointer;
    }

    #rightTools ul li:last-child {
      border: none;
    }

    #rightTools ul li a {
      display: block;
      padding-top: 15px;
      margin-bottom: 5px;
    }

    .show_code {
      display: none;
      position: absolute;
      right: 100px;
      top: 50px;
    }

    .show_code img {
      width: 150px;
      height: 150px;
    }
  </style>
  <div id="rightTools">
    <ul>
      <li>
        <a target="_blank" href="http://wpa.qq.com/msgrd?V=1&amp;uin=3144824074&amp;Site=客服&amp;Menu=yes">
          <img border="0" src="/Public/Seller/images/QQ.png" alt="点击这里给我发消息" title="点击这里给我发消息" />
        </a>
        <span>QQ客服</span>
      </li>
      <li id="wechat_code">
        <img border="0" src="/Public/Seller/images/wechat.png" style="margin-top: 20px;" />
        <br />
        <span>微信客服</span>
      </li>
      <li>
        <a href="javascript:;" class="back_to_top" title="回到顶部">
          <img border="0" src="/Public/Seller/images/top.png" />
        </a>
      </li>
    </ul>
    <div class="show_code">
      <img border="0" src="/Public/Home/images/eqcode.jpg" />
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      FancyForm.setup();
    });
    $(function () {
      $('#wechat_code').mouseover(function () {
        $('.show_code').show();
      });
      $('#wechat_code').mouseleave(function () {
        $('.show_code').hide();
      });
    })
  </script>
</body>

</html>