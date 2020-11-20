//app验证表单

$(document).ready(function(){
    $("body").bind("click",function(){
							var sum = 0;
								$.each($(".task_num"),function(){							
								sum += parseInt(this.value);
								});
							//alert(sum);
							$("#total_nums").text(sum);//每天流量个数
							var uprice=$("#uprice").val();//流量单价
							var sumtl=$('input:radio[name="pcting"]:checked').val();//停留时间
							if(sumtl > 65){
								  var uprices =  parseFloat(uprice)+0.01;//单个流量积
									}else{
										 var uprices =  parseFloat(uprice);//单个流量积
										}
							 
							var ll_total = uprices*sum;
							//alert(sumts);
							$("#total_price").text(Math.round(ll_total*100)/100);
							//$("#tas_price").text(Math.round(uprices*100)/100);
							//$("#total_price").text(ll_total);
							$("#tas_price").text(uprices);
							
						});					
});
 
//验证表单
$.validator.setDefaults({
	errorElement:'span'
});
$(function(){ 
	$( "#taskForm" ).validate({ 
		rules: { 
		task_name: {required: true}, 
		task_wang: {required: true}, 
		total_num: {required: true ,min:1}, 
		fee: {required: true ,min:0.9}, 
		oldpwd: {required: true}, 
		pwd: {required: true,minlength:6}, 
		repwd: {required: true,equalTo:'#newpwd'}, 
		email: {required: true},
		qq: {required: true},
		mobile_phone: {required: true},
		msn: {required: true},
		task_url: { 
			required: true, 
			remote:{
					type:"POST",
					url:"/checkurl.php"
					} 
			   } ,	   
		task_key: { 
			required: true, 
			minlength:2,
			remote:{
				type:"POST",
				url:"/checkkey.php",
					data: {'task_url': function(){return $('input[name="task_url"]').val();},'task_key': function(){return $('input[name="task_key"]').val();}}
					} 
				  } 
		}, 
		messages: { 
		task_name: {required: "任务名不能为空"},  
		task_wang: { required: "请输入店铺旺旺"},
		oldpwd: { required: "请输入原来密码"},
		pwd: { required: "请输入新密码",minlength:"密码不能少于6位"},
		repwd: { required: "请重复输入新密码",equalTo:"两次密码不一致,请重新输入！"},
		email: { required: "邮箱不能为空"},
		qq: { required: "QQ不能为空"},
		mobile_phone: {required: "手机号码不为空"},
		msn: { required: "支付宝不能为空"},
		total_num: { required: "请输入流量数目",min:"请设置流量"},
		fee: { required: "请输入充值金额",min:"充值金额必须大于1"},
		task_url: {required: "请输入宝贝连接", remote: "宝贝连接错误" },
		task_key: { required: "请输入关键词", minlength:"关键词大于一个字",remote:"关键词排名靠后,请换关键词" }
		} ,
		//debug: false,
		 submitHandler: function(form) { 
             $('.am-btn-primary').attr('disabled',true); 
	         $('.am-btn-primary').attr('value','提交中......'); 
			 form.submit()
            } 
	}); 
}); 
