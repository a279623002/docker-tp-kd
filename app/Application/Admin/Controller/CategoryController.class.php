<?php 
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\CategoryLogic;
class CategoryController extends BaseController {
	
	//文档分类
	public function show() {
		$Category = new CategoryLogic();
		$list = $Category->get_category();

		$this->assign('list', $list);
		$this->display();
	}
	
	//编辑、添加分类
	public function add(){
		$Category = new CategoryLogic();
		$cate_id = I('get.cate_id', '',true);
		$data = I('post.', '', true);
		
		if (!empty($cate_id)) {
			$detail = $Category->detail_category($cate_id);
			$this->assign('detail', $detail); 
		}
		if(!empty($data)) {
			$result = $Category->add_category($data);
			exit(json_encode($result));
		}

		$this->display();
	}

	//删除分类
	public function del() {
		$Category = new CategoryLogic();
		$cate_id = I('post.cate_id', '', true);
		if(!empty($cate_id)) {
			$result = $Category->del_category($cate_id);
		} else {
			$result = array('state'=>false,'msg'=>'删除失败');
		}
		exit(json_encode($result));
	}
}
?>