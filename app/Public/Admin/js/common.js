function delCategory() {
	if(confirm("如果删除栏目，子栏目和文档会一并删除，确定删除吗?")){
		return true;
	}else{
		return false;
	}
}

function yesno(txt){
	if(confirm("确定"+txt+"吗？")){
		return true;
	}else{
		return false;
	}
}
function showmenu(id) {
	var s = document.getElementById(id);
	if (s.style.display == 'block') {
		s.style.display = 'none';
		$('#'+id).prev().children('h2').removeClass('down_pic').addClass('up_pic');
	} else {
		s.style.display = 'block';
		$('#'+id).prev().children('h2').removeClass('up_pic').addClass('down_pic');
	}
}

$(function () {
	var title = $('.main .box .bhead h2').html();
	var old_title = '系统首页';
	if (title) {
		console.log(title);
		$(self.parent.frames["topFrame"].document).find('#top_title').html(title);
	} else {
		$(self.parent.frames["topFrame"].document).find('#top_title').html(old_title);
	}
})