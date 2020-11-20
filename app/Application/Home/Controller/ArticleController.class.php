<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends BaseController {
	
	//文章详情
	public function detail(){
		$id = I('get.id');
		$cate = empty($id) || $id > 3 || $id < 1 ? '1' : $id;
		$data = M('article')->where(array('cate'=>$cate))->find();
		$data['addtime'] = date('Y-m-s', $data['addtime']);
		$this->assign('data', $data);
		$this->display();
	}
}