<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<link rel="stylesheet" type="text/css" href="./images/combined.css" charset="GB2312">
<LINK rel="stylesheet" type="text/css" href="./images/thickboxpay.css" charset="GB2312"> 
<script type="text/javascript" src="./images/jquery-1.8.3.js"></script>
<SCRIPT type="text/javascript" src="./images/thickbox.js"></SCRIPT> 
<script type="text/javascript" src="./images/JScript.js"></script>
<title>֧��������</title>
<script>
$(document).ready(function() {
		var currentProCode = $("#currentProCode").val();
		var currentTab = $("#tab_" + currentProCode);
		currentTab.addClass("current");
		currentTab.siblings().removeClass("current");
		//var num = $(".select-tab li").index(currentTab);
		var num = currentTab.index();
		$(".contentwrap .paypanel").eq(num).show().siblings().hide();
		tb_show('ע������','#TB_inline?height=610&width=543&inlineId=TipWindow'); 
	})
</script>
</head>
<body>
<DIV style="DISPLAY: none" id=TipWindow> 
<DIV style="WIDTH: 513px; BACKGROUND: url(./images/tips.jpg); HEIGHT: 600px" class=TipWindow> 
<DIV> 
<UL class="clearfix"> 
<LI><% out.print(request.getParameter("payAmount"));%></LI> 
<LI><% out.print(request.getParameter("title"));%></LI></UL></DIV><A id=cat_Href onclick=tb_remove(); href="#">����֪��������һ������</A> </DIV></DIV> 
<div class="headwrap">
	<div class="head clearfix">
		<ul>
			<li class="head_logo"><a href="/" target="_blank">��վ��ҳ</a></li>
			<li class="head_link"><a href="/" target="_blank">��վ��ҳ</a></li>
		</ul>
	</div>
</div>
<!--header����-->


<div class="content">

                <div class="order">
                    <div class="order_content detail">
                        <ul class="clearfix">
							<li><span>�����</span><strong>��<% out.print(request.getParameter("payAmount"));%></strong></li>
							<li><span>�տ��ˣ�</span><strong><% out.print(request.getParameter("optEmail"));%></strong></li>
                            <li><span>����˵����</span><strong><% out.print(request.getParameter("title"));%></strong> &nbsp;</li>
                        </ul>
                
                        <div class="findetail">
                            <a href="#">ת����Ϣ</a>
                        </div>
                    </div>
                </div>
    
		<input type="hidden" id="currentProCode" value="SP000001">

                <div class="select-tab">
                    <ul>
                        <li id="tab_SP000001" class="current"><a href="#">�ֻ�֧����֧��</a></li>
                        <li id="tab_SP000002" class=""><a href="#">��ҳ֧����ת��</a></li>
                     </ul>
                </div>
        
		<div class="contentwrap">

              	<div class="paypanel" style="display: block;">
                	<div class="content_left">
  
                       <h4><span>��ֵ���̣�ȷ�ϳ�ֵ�˺ź�,���ֻ�֧����ɨ���ά����վ֧����ת��.</span></h4>

						<ul class="clearfix hided">					
                        <li><span>1.���ȴ��ֻ�֧����Ǯ��.</span></li>
                        <li><span>2.ɨ�豾վ֧������ά��,��<strong>ѡ��ת�˹���</strong></span></li>
                        <li><span>3.��������д:<strong><% out.print(request.getParameter("payAmount"));%></strong></span></li>
                        <li><span>4.����˵����д:<strong><% out.print(request.getParameter("title"));%></strong></span></li>
                        <li><span>��ܰ��ʾ�������޸ĸ������˵��<strong>���򲻷�����</strong></span></li>
                        <li><span>����ʱ�䣺����ɹ���,<strong>���ĵȴ�10����</strong>.</span></li>
                        <li><span><strong>ע�����</strong>.</span></li>
                        <li><span><strong>1.����ȷ��д����˵��,�����޷��Զ�����.</strong>.</span></li>
                        <li><span><strong>2.��վ֧�����˻��᲻���ڸ���,ÿ�γ�ֵǰ����غ˶�֧�����˺�.</strong>.</span></li>
						</ul>
                      
                     </div>
                     <div class="content_right_m">
                     <img src="./images/QR.png" style="margin-top:20px; padding-top:20px;margin-left:20px; padding-left:20px">
                     </div>
				</div>
				

				<div class="paypanel" style="display: none;">
                	<div class="content_left">
                		<h4><span>��ֵ���̣�ȷ�ϳ�ֵ�˺ź�,��¼��ҳ֧���������ǵ�֧����ת��.</span></h4>
                    
                        <ul class="clearfix">
                            <li><span>1.�������¼��ҳ֧����.</span></li>
                            <li><span>2.��վ֧�����˺�:<strong><% out.print(request.getParameter("optEmail"));%></strong> ת��
                             <strong>��<% out.print(request.getParameter("payAmount"));%></strong> Ԫ.</span></li>
                            <li><span>3.����˵������д:<strong><% out.print(request.getParameter("title"));%></strong></span></li>
                            <li><span>��ܰ��ʾ�������޸ĸ������˵��<strong>���򲻷�����</strong></span></li>
                            <li><span>����ʱ�䣺����ɹ���,<strong>���ĵȴ�10����</strong>.</span></li>
                            <li><span><strong>ע�����</strong>.</span></li>
                            <li><span><strong>1.����ȷ��д����˵��,�����޷��Զ�����.</strong>.</span></li>
                            <li><span><strong>2.��վ֧�����˻��᲻���ڸ���,ÿ�γ�ֵǰ����غ˶�֧�����˺�.</strong></span></li>
                         </ul>
                         <div class="btn"><a target="_blank" href="https://auth.alipay.com/login/index.htm?goto=https://shenghuo.alipay.com/send/payment/fill.htm?title=<% out.print(request.getParameter("title"));%>" id="sino_Href">��¼֧��������</a></div>
                         
                         
                     </div>
                     <div class="content_right_pc">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="410" height="410" id="alipay" align="middle">
				<param name="movie" value="./images/alipay.swf">
				<param name="quality" value="high">
				<param name="bgcolor" value="#ffffff">
				<param name="play" value="true">
				<param name="loop" value="true">
				<param name="wmode" value="window">
				<param name="scale" value="showall">
				<param name="menu" value="true">
				<param name="devicefont" value="false">
				<param name="salign" value="">
				<param name="allowScriptAccess" value="sameDomain">
				<!--<![endif]-->
			</object>
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="./images/alipay.swf" width="410" height="410">
					<param name="movie" value="css/img/alipay.swf">
					<param name="quality" value="high">
					<param name="bgcolor" value="#ffffff">
					<param name="play" value="true">
					<param name="loop" value="true">
					<param name="wmode" value="window">
					<param name="scale" value="showall">
					<param name="menu" value="true">
					<param name="devicefont" value="false">
					<param name="salign" value="">
					<param name="allowScriptAccess" value="sameDomain">
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="./images/get_flash_player.gif" alt="��� Adobe Flash Player">
					</a>
				<!--[if !IE]>-->
				</object>
                     </div>
				</div>
			
		</div>
</div>


<div id="footer">
	<div class="foot-link">

	</div>
	<div class="copyright">Copyright 2016. All Rights Reserved  </div>
</div>

</body></html>