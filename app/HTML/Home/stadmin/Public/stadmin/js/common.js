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
function showmenu(id){
	var s=document.getElementById(id);
	if(s.style.display=='block'){
		s.style.display='none';
	}else{
		s.style.display='block';
	}
}