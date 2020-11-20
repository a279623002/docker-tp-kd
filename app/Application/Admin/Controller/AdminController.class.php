<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\AdminLogic;
class AdminController extends BaseController {
	
	public function show() {
		$AdminLogic = new AdminLogic();
		$list = $AdminLogic->get_admin_list();
		$this->assign('list', $list['list']);
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->display();
	}

	//添加、编辑管理员
	public function adminAdd() {
		$AdminLogic = new AdminLogic();
		$admin_id = I('get.admin_id', '', true);
		if(!empty($admin_id)) {
			$detail = $AdminLogic->get_admin_info($admin_id);
			$this->assign('detail', $detail);
		}
		$data = I('post.', '', true);
		if(!empty($data)) {
			$result = $AdminLogic->admin_add($data);
			exit(json_encode($result));
		}
		$this->display();
	}

	//删除管理员
	public function adminDel() {
		$AdminLogic = new AdminLogic();
		$admin_id = I('post.admin_id', '', true);
		if(!empty($admin_id)) {
			$result = $AdminLogic->admin_del($admin_id);
		}else {
			$result = array('state'=>false, 'msg'=>'未指定管理员!');
		}
		exit(json_encode($result));
	}
}