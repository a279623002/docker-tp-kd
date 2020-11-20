<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	
    public function index(){
    	$t = I('get.t', '', true);
    	if (!empty($t)){
			session('t', $t);
			header('location:'.U('Home/Seller/register'));
		}
		$seller_id = session('seller_id');
		$seller = session('seller');
		$flink = M('flink')->order('sort desc')->select();
		$notice = M('system')->getField('index_notice');
        $this->assign('notice', $notice);
		$this->assign('seller_id',$seller_id);
		$this->assign('seller',$seller);
		$this->assign('flink', $flink);
    	$this->display();
    }
}