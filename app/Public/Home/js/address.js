// 手机号码验证
jQuery.validator.addMethod("isMobile", function(value, element) {
    var length = value.length;
    var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "请正确填写您的手机号码");
//验证表单
$.validator.setDefaults({
	errorElement:'span'
});

$(document).ready(function(){
    $("body").bind("click",function(){
							var sum = 0;
							$.each($("#w1"),function(){ sum += Number(this.value);});
							var ll_total = sum+0.5;
							//alert(ll_total);
							//$("#w2").text(ll_total);//客单价 
							$('#w2').attr('value',ll_total); 
							
						});					
});

$(function(){ 
	$( "#taskForm" ).validate({ 
		rules: { 
		a_dianpu: {required: true}, 
		a_name: {required: true}, 
		a_phone: {required: true ,minlength: 11,isMobile: true}, 
		a_yanzma: { 
			required: true, 
			remote:{
				type:"POST",
				url:"/Member/yzs/",
					data: {'jihao': function(){return $('input[name="a_phone"]').val();},'yanzma': function(){return $('input[name="a_yanzma"]').val();}}
					} 
		}, 
		a_province: {required: true}, 
		a_city: {required: true},
		a_district: {required: true},
		a_address: {required: true},
		weight:{required: true,range:[0.05,40]}
		}, 
		messages: { 
		a_dianpu: {required: "店铺名不能为空"},  
		a_name: { required: "发件人不能为空"},
		a_phone: { required: "号码不能为空",minlength: "手机不能小于11位数",isMobile: "请正确填写您的手机号码"},
		a_yanzma: { required: "验证码不能为空",remote: "验证码不正确,请重新输入"},
		a_province: { required: "省份不能为空"},
		a_city: { required: "城市不能为空"},
		a_district: { required: "区域不能为空"},
		a_address: { required: "不为空"},
		weight: {required: "包裹重量不为空",range:"在 0.05-40.00kg 内"}
		} ,
		//debug: false,
		submitHandler: function(form) { 
             $('.am-btn-warning').attr('disabled',true); 
	         $('.am-btn-warning').attr('value','提交中......'); 
			 form.submit()
            } 
	}); 
}); 

function yanzma(){
	  var jihao=$("#jihao").val();//流量单价
	  if(jihao==""){
		alert("号码不能为空！");
		$("#jihao").focus();
			return false;
	   }
	 
	 $.ajax({
		url:'/Member/yz/',//你对数据库的操作路径
		data:{//这是参数
		jihao:jihao
		},
		type:'post',//提交方式
		success:function(data){//后台处理数据成功后的
		data=data.replace(/\s/g,'');
		 if(data == "1"){
			 $('#jihao').attr('readonly',true); 
			 $('.am-btn-sm').attr('disabled',true); 
	       //  $('.am-btn-sm').attr('value','提交中......'); 
			 $('.am-btn-sm').text("验证已发送,注意查收");
			 }else{
			 alert("发送失败,请重新输入"); 
			}
		},
		error:function(data){
		     alert(data);
		}
	})
	 
}
function checkisnos(){ 

			  //是否错误标识
			  var addtext =$("#a_address ").val().replace(/^(\s|\u00A0)+|(\s|\u00A0)+$/g,""); 
			   $("#a_address").val(addtext); 
			  addtext =$('#a_address').val();
			   if(addtext==""){
				alert("请您填写收货地址！");
				$("#a_address").focus();
				return false;
		      } 
			//  isno=1;
		 	  var addtextarr= new Array(); 
			  var adddan=new Array();
			  addtextarr=addtext.split("\n");
			//addstr="";
			 // addinputarr="";
			  addsum=0;
			  //最多一次只能提交100个
			  if(addtextarr.length<=30){  
			   for(i=0;i<addtextarr.length;i++){
				 if(addtextarr[i]!='' && trim(addtextarr[i])!=''){ 
				 addtextarr[i] = addtextarr[i].replace(/\'/g, "");
				// addtextarr[i] = addtextarr[i].replace(/\,/g, "，");
				// addinputarr+="<input type='hidden' name='addinputarr["+addsum+"]' value='"+addtextarr[i]+"' >";  
				 adddan=trim(tihuandou(addtextarr[i])).split("，");
				 addsum+=1;
				//保存表单数据方便提交
				if (contains(tihuandou(addtextarr[i]), ",", true)){  
                alert("第"+addsum +"条的地址含有英文逗号,请更换为中文逗号，"); return; 
                }
				// if(adddan.length!=5 && adddan.length!=4){alert("第"+addsum +" 个收货地址格式有错误，请仔细检查！");  isno=0;return; }
				if(adddan[1].length!=11){alert("第"+addsum +"的手机号码不是11位或者没有，隔开，请仔细检查！"); return;}
				if(adddan.length!=4){alert("第"+addsum +" 个收货地址格式有错误，请仔细检查！"); return; }
				if(adddan.length==4)
				 {
				 	  var shouhuodizhi = adddan[2];//获取收件地址 
				 } else if(adddan.length==5){
					  var shouhuodizhi = adddan[3];//获取收件地址 
					  }
				 var shdz_array =trim(shouhuodizhi).split(" ");
				 if(shdz_array.length<4)
				 {
				 	     alert("第"+addsum +" 个收货地址格式中省、市、县之间应该用空格隔开，请仔细检查！"); return;
     			 }
     			 //判断地址之间空格是否是多个
     			 if(exists_multispace(trim(shouhuodizhi)))
     			 {
     			 	 alert("第"+addsum +" 个收货地址格式中省、市、县之间只能用一个空格隔开，请仔细检查！"); return;
     			 }  
				}else{alert("第"+(i+1)+"个地址不能为空,请删除空数据");return; }
			 }alert("验证通过,可以提交");$('.am-btn-warning').attr('disabled',false);
			 
	     } else{
				  alert("亲，一次最多只能批量下30单，请减少收货地址！"); return;
				  } 
			 //is_enabled =isno;
			  // return(isno);
		 } 
		 
function trim(str){   
		 str = str.replace(/^(\s|\u00A0)+/,'');   
			for(var i=str.length-1; i>=0; i--){   
			  if(/\S/.test(str.charAt(i))){   
			    str = str.substring(0, i+1);   
 				  break;   
			 }   
		 }   
		 return str;   
	    }  
function exists_multispace(str){
     	  var rtn_result=false;
     	  var str_length_old=str.length;
     	  str = str.replace(/\  /g, " ");
     	  var str_length =str.length;
     	  if(str_length_old !=str_length )
     	  {
     	  	  rtn_result = true;
     	  } 
     	  return rtn_result;
     } 	
	function tihuandou(str){
			var reg=new RegExp(",","g"); //创建正则RegExp对象  
			var stringObj=str;  
			var rtn_result=stringObj.replace(reg,"，");  
     	    return rtn_result;
     }	
/**
 * 判断 str 字符串中是否含有字符串 subStr
 * @param {} str 原字符串
 * @param {} subStr 要查找的字符串
 * @param {} isIgnoreCase 是否忽略大小写
 * @return {Boolean}
 */
function contains(str, subStr, isIgnoreCase) {
	
	if (isIgnoreCase) {
		// 忽略大小写
		str = str.toLowerCase();
		subStr = subStr.toLowerCase();
	}
	
	var startChar = subStr.substring(0,1);
	var strLen = subStr.length;
	
	for (var j=0; j<str.length-strLen+1; j++) {
		if (str.charAt(j) == startChar) {
			/* 如果匹配起始字符,开始查找 */
			if (str.substring(j, j+strLen) == subStr) {
				/*如果从j开始的字符与 str 匹配 */
				return true;
			}
		}
	}
	return false;
}	 	 