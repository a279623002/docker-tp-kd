<?php 
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\SellerLogic;
class PayController extends BaseController {
	
	//充值记录列表
	public function record() {
		$SellerLogic = new SellerLogic();
		$where = I('get.', '', true);
		$list = $SellerLogic->get_record_list($where);
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->display();
	}
	
	//充值记录删除
	public function recordDel() {
		$SellerLogic = new SellerLogic();
		$charge_id = I('post.charge_id', '', true);
		if(!empty($charge_id)) {
			$result = $SellerLogic->record_del($charge_id);
		} else {
			$result = array('state'=>false, 'msg'=>'未指定记录!');
		}
		exit(json_encode($result));
	}
	
	//用户等级
	public function alishow() {
		$SellerLogic = new SellerLogic();
		$list = $SellerLogic->get_grade_list();
		$this->assign('list', $list);
		$this->display();
	}

	//用户等级修改
	public function alishowEdit() {
		$SellerLogic = new SellerLogic();
		$level_id = I('get.level_id', '', true);
		if(!empty($level_id)) {
			$detail = $SellerLogic->grade_edit($level_id);
			$this->assign('detail', $detail);
		}
		$data = I('post.', '', true);
		if(!empty($data)) {
			$result = $SellerLogic->grade_update($data);
			exit(json_encode($result));
		}
		$this->display();
	}


	//会员支付
	public function aliok() {
		$SellerLogic = new SellerLogic();
		$charge_id = I('get.charge_id', '', true);
		if(!empty($charge_id)) {
			$data = $SellerLogic->get_record($charge_id);
			$this->assign('data', $data);
		}
		$where = I('post.', '', true);
		if(!empty($where)) {
			$result = $SellerLogic->recharge_record($where);
			exit(json_encode($result));
		}
		$this->display();
	}
}