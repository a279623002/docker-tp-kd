<?php 
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\SellerLogic;
class SellerController extends BaseController {
	
	//会员列表
	public function ushow() {
		$SellerLogic = new SellerLogic();
		$where = I('get.', '', true);
		$list = $SellerLogic->get_user_list($where);
		
		// $this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->display();
	}
	
	//会员密码修改
	public function changePw() {
		$SellerLogic = new SellerLogic();
		$data = I('post.', '', true);
		if(!empty($data['user_id']) && !empty($data['new_password'])) {
			$result = $SellerLogic->changePw($data);
		}else {
			$result = array('state'=>false, 'msg'=>'缺少必要值');
		}
		exit(json_encode($result));
	}

	//冻结会员
	public function freeze() {
		$SellerLogic = new SellerLogic();
		$data = I('post.', '', true);
		$result = $SellerLogic->freeze($data);
		exit(json_encode($result));
	}

	//添加会员
	public function addSeller() {
		$SellerLogic = new SellerLogic();
		$seller_id = I('get.seller_id', '', true);
		if(!empty($seller_id)) {
			$detail = $SellerLogic->get_user_detail($seller_id);
			$this->assign('detail', $detail);
		}
		$data = I('post.', '', true);
		if(!empty($data)) {
			$result = $SellerLogic->add_user($data);
			exit(json_encode($result));
		}
		$this->display();
	}

	// 会员金额记录
	public function ulog() {
		$SellerLogic = new SellerLogic();
		$seller_id = I('get.seller_id', '', true);
		if(!empty($seller_id)) {
			$list = $SellerLogic->get_log_list($seller_id);
			$this->assign('list', $list);
		}
		$this->display();
	}

	//发货地址
	public function address() {
		$SellerLogic = new SellerLogic();
		$key = I('get.key', '', true);
		$list = $SellerLogic->get_user_address($key);
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->display();
	}
	
	//删除用户发货地址
	public function delAddress() {
		$SellerLogic = new SellerLogic();
		$id = I('post.id', '', true);
		if(!empty($id)) {
			$result = $SellerLogic->del_address($id);
		}else {
			$result = array('state'=>false, 'msg'=>'删除失败!');
		}
		exit(json_encode($result));
	}

	//推广记录
	public function union() {
		$SellerLogic = new SellerLogic();
		$list = $SellerLogic->get_user_union();

		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->assign('sum', $list['sum']);
		$this->display();
	}
	
	//消费记录
	public function score() {
		$SellerLogic = new SellerLogic();
		$where = I('get.', '', true);
		$list = $SellerLogic->get_user_score($where);

		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		
		$this->display();
	}
	//删除消费记录
	public function scoreDel() {
		$SellerLogic = new SellerLogic();
		$f_id = I('post.f_id', '', true);
		if(!empty($f_id)) {
			$result = $SellerLogic->del_user_score($f_id);
			exit(json_encode($result));
		}else {
			exit(json_encode(array('state'=>false, 'msg'=>'未指定记录!')));
		}
	}
	
	//空包记录
	public function exlist() {
		$SellerLogic = new SellerLogic();
		$where = I('get.', '', true);
		$list = $SellerLogic->get_task($where);
		
	
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		
		$this->display();
	}

	//发货任务确认
	public function taskConfirm() {
		$SellerLogic = new SellerLogic();
		$task_id = I('post.task_id', '', true);
		if(!empty($task_id)) {
			$result = $SellerLogic->task_confirm($task_id);
		} else {
			$result = array('state'=>false, 'msg'=>'未指定任务!');
		}
		exit(json_encode($result));
		
	}

	//发货任务删除
	public function taskDel() {
		$SellerLogic = new SellerLogic();
		$task_id = I('post.task_id', '', true);
		if(!empty($task_id)) {
			$result = $SellerLogic->task_del($task_id);
		} else {
			$result = array('state'=>false, 'msg'=>'未指定任务!');
		}
		exit(json_encode($result));
		
	}
}