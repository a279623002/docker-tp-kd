<?php 
//用户密码加密
function encrypt($str){
	return md5(C("AUTH_CODE").$str);
}

//上传导入文件
function upload_excel($excel){
	$upload = new \Think\Upload();// 实例化上传类
	$upload->maxSize   =     3145728 ;// 设置附件上传大小
	$upload->exts      =     array('xls');// 设置附件上传类型
	$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
	$upload->savePath  =     'Excel/'; // 设置附件上传（子）目录
	$upload->subName   =     array('date', 'Ym');
	$info   =   $upload->upload();
	$file_name =  $upload->rootPath.$info['excel']['savepath'].$info['excel']['savename'];
	return $file_name;
}

/** 处理上传exl数据
 * $file_name  文件路径
 */
function import_plate_exl($file_name){
	import("Org.Util.PHPExcel");   // 这里不能漏掉
	$shared = new \PHPExcel_Shared_Date();
	$objReader = \PHPExcel_IOFactory::createReader('Excel5');
	$objPHPExcel = $objReader->load($file_name,$encode='utf-8');
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumn = $sheet->getHighestColumn(); // 取得总列数

    for($i=1;$i<$highestRow+1;$i++){
		$data[$i]['order_no']  = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
		$data[$i]['goods_weight']  = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
		if(is_object($data[$i]['order_no']))  $data[$i]['order_no'] = $data[$i]['order_no']->__toString();
		if(is_object($data[$i]['goods_weight']))  $data[$i]['goods_weight'] = $data[$i]['order_no']->__toString();
	}
	unset($data[1]);
	sort($data);
	return $data;
}

/**
 * Programmer : manty
 * @param unknown $images
 * 上传友情链接图函数
 */
function upload_image($image){
	if (!empty($image)){
		$upload = new \Think\Upload();
		$upload->maxSize = (1024*1024*1024)*2;
		$upload->allowExts  = array(
				'jpg', 'gif', 'png', 'jpeg'
		);
		$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		$upload->savePath  =     'shop/'; // 设置附件上传（子）目录
		$upload->replace = true;
		$upload->autoSub = false;
		$imginfo = $upload->upload();
		if (empty($imginfo)){
			// $this->error($upload->getError());
		}else{
			return $imginfo;
		}
	}
}

/**
 * TODO 基础分页的相同代码封装
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage($count, $pagesize = 15) {
	$p = new Think\Page($count, $pagesize);
	$p->setConfig('header', '<br /><li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	$p->setConfig('prev', '上一页');
	$p->setConfig('next', '下一页');
	$p->setConfig('last', '末页');
	$p->setConfig('first', '首页');
	$p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
	$p->lastSuffix = false;//最后一页不显示为总页数
	return $p;
}


/**
 * Thinkphp默认分页样式转amazeui分页样式
 * @author zero
 * @param string $page_html tp默认输出的分页html代码
 * @return string 新的分页html代码
 */
function amazeui_page_style($page_html){
    if ($page_html) {
        $page_show = str_replace('<div>','<ul data-am-widget="pagination" class="am-pagination am-pagination-default am-pagination-right">',$page_html);
        $page_show = str_replace('</div>','</ul>',$page_show);
 
        $page_show = str_replace('<span class="current">','<li class="am-active"><a class="am-active">',$page_show);
        $page_show = str_replace('</span>','</a></li>',$page_show);
        $page_show = str_replace('<a class="prev"','<li class="am-pagination-prev"><a',$page_show);
        $page_show = str_replace('<a class="next"','<li class="am-pagination-next"><a',$page_show);
 
        // $page_show = str_replace(array('<a class="num"','<a class="am-pagination-prev"','<a class="am-pagination-next"','<a class="am-pagination-last"','<a class="m-pagination-first"'),'<li><a',$page_show);
        $page_show = str_replace(array('<a class="num"'),'<li><a',$page_show);
        $page_show = str_replace('</a>','</a></li>',$page_show);
    }
    return $page_show;
}




?>