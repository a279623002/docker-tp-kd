/**
 * AppOpenPlatform 类, 命名空间，最高层
 */
var AOP = AppOpenPlatform = {};
 

// global
AOP.Global = (function(){
	return {
		Init: "init"
	}
})();

// config
AOP.Config = (function() {
	return {
		BASE_URL: ""
	}
})();
 
 


/**
 * 程序主入口
 * 
 * @method
 */
$(function() {
	(function(obj) {
		for (var key in obj) {
			if (sl.extendBase.getType(obj[key]) == sl.extendBase.Config.Function && 
				key == AOP.Global.Init) {
				obj[key]();
			}

			if (sl.extendBase.getType(obj[key]) == sl.extendBase.Config.Object) {
				arguments.callee(obj[key]);
			}

			AOP.Config.LOGOUT_BACK = AOP.Config.LOGIN_BACK = AOP.Config.CHANGE_PASS_BACK = AOP.Config.BASE_URL = $("#openUrl").val();
			AOP.Config.REGIEST_BACK = $("#openUrl").val() + "/userinfo";
			AOP.Config.LOGIN_HASH = $("#openLogin").val();
			AOP.Config.ADMIN_LOGIN_BACK = AOP.Config.LOGIN_BACK + "/admin";
			AOP.Config.REGIEST_HASH = $("#casReg").val();
			AOP.Config.LOGOUT_HASH = $("#casLogOut").val();
			AOP.Config.CHANGE_PASS_HASH = $("#casChangePass").val();
		}
	 
	})(AOP);
	
});

/**
 * AOP.Welcome 欢迎界面处理类
 */
AOP.Welcome = (function() {
	return {
		// 构造方法
		init: function() {
			this.bannerInit();
			// 事件绑定
			$("#toAddMyApp").on("click", this.toAddMyApp);
			$("#submitApp, #applyApp, #publishApp, #jiyou").on("click",this.submitApp);
			
			$("#welPhone").on("change",this.checkMobile);
			$("#welAuthCodeSend").on("click",this.getAuthCode);
			$("#welPhoneAuthCode").on("change",this.verifyAuthCode);
			$("#submitWelPhone").on("click",this.submitWelPhone);
			
			// 设置导航菜单高亮
			 
		},
		
		submitApp : function(){
			AOP.Base.checkDevAuditFail(function(){ window.location = AOP.Config.BASE_URL + AOP.Config.ADD_APP; }, AOP.Config.ADD_APP);
		},
		
		// banner 初始化
		bannerInit: function(){
			$("#indexBanner").slide({
				mainCell:".bd ul",
				autoPlay:true,
				delayTime: 1000,
				interTime: 3000,
				effect: "fold",
				trigger:"mouseover",
				easing:"easeOutExpo",
				endFun: function(index) {
					var slideBoxLi = $(".bd ul li");
					for (var i = 0, len = slideBoxLi.length; i < len; ++i) {
						var item = $(slideBoxLi[i]);
						if(i == index) {
							item.addClass('on');
						} else{
							item.removeClass('on');
						}
					}
				}
			});
		},
		
		 
	 
		submitWelPhone : function(){
			if($("#welPhoneError").hasClass('span-phone-txt-err') || $("#submitPhoneError").hasClass('span-code-txt-err') || $("#submitWelPhone").hasClass('po-btn-disable')){
				return;
			}
			$("#submitWelPhone").html('提交中...').addClass('po-btn-disable');
			$.ajax({
				url : "/submitPhone",
				data : {
					phone : $("#welPhone").val(),
					authCode : $("#welPhoneAuthCode").val()
				},
				type : "POST",
				success : function(result) {
					if(result && result.ok == 1){
						$("#oldPhoneVerify").hide();
					}
					if(result && result.error){
						$("#submitPhoneError").html('<i></i>'+result.error).addClass('span-code-txt-err');
					}
					$("#submitWelPhone").html('提交').removeClass('po-btn-disable');
				}
			});
		},
		
		// 添加 App
		toAddMyApp: function() {
			window.location = AOP.Config.BASE_URL + AOP.Config.ADD_APP;
		}
	}
})();
