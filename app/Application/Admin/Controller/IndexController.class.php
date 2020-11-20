<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\AdminLogic;
class IndexController extends BaseController {
	
    public function index(){
    	
    	$this->display();
    }
    
    //统计
    public function tongji(){
		$AdminLogic = new AdminLogic();
		$list = $AdminLogic->tongji();
		$this->assign('list', $list);
    	$this->display();
    }
    
    //数据排行
    public function userinfo(){
		$AdminLogic = new AdminLogic();
		$where = I('get.', '', true);
		$list = $AdminLogic->get_user_info($where);

		$this->assign('page', $list['page']->show());
		$this->assign('list', $list['list']);
    	$this->display();
    }
    
    //系统参数
    public function config(){
		$AdminLogic = new AdminLogic();
		$data = I('post.', '', true);
		if(!empty($data)) {
			$result = $AdminLogic->system_add($data);
			exit(json_encode($result));
		}
		$detail = $AdminLogic->system_detail();
		$this->assign('detail', $detail);
    	$this->display();
    }
    
    //友情链接
    public function fline(){
		$AdminLogic = new AdminLogic();
		$list = $AdminLogic->get_fline_list();
		$this->assign('list', $list);
    	$this->display();
	}
	
    //友情链接编辑、添加
    public function flineAdd(){
    	$AdminLogic = new AdminLogic();
		$data = I('post.', '', true);
		$flink_id = I('get.flink_id');
		if (!empty($flink_id)){
			$detail = $AdminLogic->get_flink_detail($flink_id);
			$this->assign('detail', $detail);
		}
		if (!empty($data)){
			$Model = M('flink');
			$image = empty($_FILES['pic']) ? '' : $_FILES['pic'];
			if ($image['error'] == 0){
				$data['pic'] = upload_image($image);
			}
			$result = $AdminLogic->flinkAdd($data);
			exit(json_encode($result));
		}
    	$this->display();
	}
	
	//友情链接删除
	public function flineDel() {
		$AdminLogic = new AdminLogic();
		$flink_id = I('post.flink_id', '', true);
		if(!empty($flink_id)) {
			$result = $AdminLogic->flink_del($flink_id);
		}else {
			$result = array('state'=>false, 'msg'=>'未指定连接!');
		}
		exit(json_encode($result));
	}
    
	public function login(){
    	try {
    		$data = I('post.');
    		if (!empty($data)){
    			$AdminLogic = new AdminLogic();
    			$result = $AdminLogic->login($data);
    			exit(json_encode($result));
    		}
    
    	} catch (\Exception $e) {
    		$result = array('state'=>false,'msg'=>$e->getMessage());
    	}
    	$this->display();
    }
    
    public function logout(){
    	setcookie('admin_id','',time()-3600,'/');
    	setcookie('account','',time()-3600,'/');
    	session_unset();
    	session_destroy();
    	header("location:".U('Admin/Index/login'));
    	exit;
    }
    
    public function top(){
    	$time = date('Y-m-d h:i:s', time());
    	$this->assign('time', $time);
    	$this->display();
    }
    public function left(){
    	$this->display();
    }
    public function main(){
		$AdminLogic = new AdminLogic();
		$data = $AdminLogic->get_data();
		$this->assign('data', $data['data']);
		$this->assign('task', $data['task']);
    	$this->display();
    }
}