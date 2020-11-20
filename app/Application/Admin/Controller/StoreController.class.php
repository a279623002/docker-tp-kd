<?php 
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\SellerLogic;
class StoreController extends BaseController {
	
	//商店列表
	public function shop() {
		$SellerLogic = new SellerLogic();
		$where = I('get.', '', true);
		$list = $SellerLogic->get_shop_list($where);
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->display();
	}

	//商店审核
	public function auditing() {
		$SellerLogic = new SellerLogic();
		$data = I('post.', '', true);
		$result = $SellerLogic->auditing($data);
		exit(json_encode($result));
	}

	//续费价格列表
	public function renew (){
		$SellerLogic = new SellerLogic();
		$list = $SellerLogic->get_renew_list();
		$this->assign('page', amazeui_page_style($list['page']->show()));
		$this->assign('list', $list['list']);
		$this->display();
	}

	//续费价格添加
	public function renewEdit (){
		$SellerLogic = new SellerLogic();
		$renew_id = I('get.renew_id', '', true);
		if (!empty($renew_id)) {
			$detail = $SellerLogic->renew_detail($renew_id);
			$this->assign('detail', $detail);
		}
		$data = I('post.', '', true);
		if (!empty($data)) {
			$result = $SellerLogic->renew_add($data);
			exit(json_encode($result));
		}
		

		$this->display();
	}

	//续费价格删除
	public function delRenew (){
		$SellerLogic = new SellerLogic();
		$renew_id = I('post.renew_id', '', true);
		$result = $SellerLogic->renew_del($renew_id);
		exit(json_encode($result));

	}

	//店铺编辑
	public function editShop () {
		$SellerLogic = new SellerLogic();
		$store_id = I('get.store_id', '', true);
		$detail = $SellerLogic->get_shop_detail($store_id);
		$data = I('post.', '', true);
		if(!empty($data['store_id'])) {
			$result = $SellerLogic->shop_edit($data);
			exit(json_encode($result));
		}
		$this->assign('detail', $detail);
		$this->display();
	}

	

}