function checkmyform(){
	if(document.myform.uname.value==''){
		alert('请填写用户名');
		document.myform.uname.focus();
		return false;
	}
	if(document.myform.pwd.value==''){
		alert('请填写密码');
		document.myform.pwd.focus();
		return false;
	}
	document.myform.submit();
}

function checkpwd(){
	if(document.myform.uname.value==''){
		alert('请填写用户名');
		document.myform.uname.focus();
		return false;
	}
	if(document.myform.email.value==''){
		alert('请填写Email');
		document.myform.email.focus();
		return false;
	}
	document.myform.submit();
}

function checkregform(){
	if(document.myform.uname.value==''){
		alert('请填写用户名');
		document.myform.uname.focus();
		return false;
	}
	if(document.myform.pwd.value==''){
		alert('请填写密码');
		document.myform.pwd.focus();
		return false;
	}
	if(document.myform.repwd.value!=document.myform.pwd.value){
		alert('两次密码输入不一致')
		document.myform.repwd.focus();
		return false;
	}
	if(document.myform.qq.value==''){
		alert("请填写QQ号码");
		document.myform.qq.focus();
		return false;
	}
	qq=document.myform.qq.value;
	if(!/^[1-9][0-9]{4,9}$/.test(qq)){
		alert('请正确填写QQ号码');
		document.myform.qq.focus();
		return false;
	}
	if(document.myform.verifys.value==''){
		alert("请填输入验证码");
		document.myform.verifys.focus();
		return false;
	}
	document.myform.submit();
}

(function(){
	var defaultInd = 0;
	var list = $('#js_ban_content').children();
	var count = 0;
	var change = function(newInd, callback){
		if(count) return;
		count = 2;
		$(list[defaultInd]).fadeOut(400, function(){
			count--;
			if(count <= 0){
				if(start.timer) window.clearTimeout(start.timer);
				callback && callback();
			}
		});
		$(list[newInd]).fadeIn(400, function(){
			defaultInd = newInd;
			count--;
			if(count <= 0){
				if(start.timer) window.clearTimeout(start.timer);
				callback && callback();
			}
		});
	}
	
	var next = function(callback){
		var newInd = defaultInd + 1;
		if(newInd >= list.length){
			newInd = 0;
		}
		change(newInd, callback);
	}
	
	var start = function(){
		if(start.timer) window.clearTimeout(start.timer);
		start.timer = window.setTimeout(function(){
			next(function(){
				start();
			});
		}, 8000);
	}
	
	start();
	
	$('#js_ban_button_box').on('click', 'a', function(){
		var btn = $(this);
		if(btn.hasClass('right')){
			//next
			next(function(){
				start();
			});
		}
		else{
			//prev
			var newInd = defaultInd - 1;
			if(newInd < 0){
				newInd = list.length - 1;
			}
			change(newInd, function(){
				start();
			});
		}
		return false;
	});
	
})();

var FancyForm=function(){
	return{
		inputs:".reg-form input",
		setup:function(){
			var a=this;
			this.inputs=$(this.inputs);
			a.inputs.each(function(){
				var c=$(this);
				a.checkVal(c)
			});
			a.inputs.live("keyup blur",function(){
				var c=$(this);
				a.checkVal(c);
			});
		},checkVal:function(a){
			a.val().length>0?a.parent("div").addClass("val"):a.parent("div").removeClass("val")
		}
	}
}();
function fleshVerify(){ 
    //重载验证码
    var time = new Date().getTime();
        document.getElementById('verifyImg').src= '/Public/verify/?'+time;
 }