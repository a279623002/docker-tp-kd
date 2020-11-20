<?php 
namespace Admin\Logic;
use Think\Model\RelationModel;
class AdminLogic extends RelationModel{
	
	//管理员登录验证
	public function login($data) {
		$admin = M('admin')->where(array('account'=>$data['account']))->find();
		if (!$admin) return array('state'=>false,'msg'=>'管理员账号不存在!');
		if (encrypt($data['password']) != $admin['password'])
			return array('state'=>false,'msg'=>'管理员密码不正确!');
		$row = array(
				'admin_id'         => $admin['admin_id'],
				'login_time'       => time(),
				'login_ip'         => $_SERVER['REMOTE_ADDR'],
		);
		if (M('admin')->save($row)){
			session('admin_id', $admin['admin_id']);
			session('role', $admin['role']);
			setcookie('admin_id', $admin['admin_id'],null,'/');
			setcookie('account', $admin['account'],null,'/');
			return  array('state'=>true,'msg'=>'登录成功','data'=>$admin);
		}else{
			return  array('state'=>false,'msg'=>'系统异常,请重新登录');
		}
	}
	
	//取管理员账号
	public function get_admin_account($admin_id) {
		$account = M('admin')->where("admin_id=$admin_id")->field('account,role')->find();
		return $account;
	}
	
	//取管理员列表
	// public function get_admin_list($where){
	// 	$Model = M('admin');
	// 	$where = array_filter($where);
	// 	$count = $Model->where($where)->count();
	// 	$p = getpage($count,10);
	// 	$adminList = $Model->where($where)->limit($p->firstRow, $p->listRows)->order('admin_id desc')->select();
	// 	foreach ($adminList as $key=>$value){
	// 		$adminList[$key]['login_time'] = date('Y-m-d H:i:s', $adminList[$key]['login_time']);
	// 		switch ($adminList[$key]['role']) {
	// 			case 1:
	// 				$adminList[$key]['role_name'] = '普通管理员';
	// 			    break;
	// 			case 2:
	// 				$adminList[$key]['role_name'] = '题库管理员';
	// 				break;
	// 			case 3:
	// 				$adminList[$key]['role_name'] = '试题员';
	// 				break;
	// 			case 4:
	// 				$adminList[$key]['role_name'] = '进程管理员';
	// 				break;
	// 			default:
	// 				$adminList[$key]['role_name'] = '超级管理员';
	// 			    break;
	// 		}
	// 		$adminList[$key]['status_name'] = $adminList[$key]['status'] == '0' ? '正常' : '异常';
	// 	}
	// 	return $adminList;
	// }
	
	//取管理员详情
	public function get_admin_info($admin_id) {
		$detail = M('admin')->where("admin_id=$admin_id")->find();
		return $detail;
	}
	
	//添加管理员
	public function adminAdd($data) {
		$Model = M('admin');
		if($this->validate_admin($data['account'])){
			$data['addtime'] = time();
			$data['password'] = encrypt($data['password']);
			if ($Model->add($data)){
				$result = array('state'=>true,'msg'=>'添加成功!');
			}else{
				$result = array('state'=>false,'msg'=>'添加失败!');
			}
		}else{
			$result = array('state'=>false,'msg'=>'账号已存在!');
		}
		return $result;
	}
	
	//管理员修改密码
	public function adminPasswordEdit($data){
		$Model = M('admin');
		$admin = $this->get_admin_info($data['admin_id']);
		if (!$admin) return array('state'=>false,'msg'=>'管理员账号不存在!');
		if (encrypt($data['password']) != $admin['password']){
			return array('state'=>false,'msg'=>'管理员密码不正确!');
		}else{
			$data['password'] = encrypt($data['new_password']);
			if ($Model->save($data)){
				$result = array('state'=>true,'msg'=>'修改密码成功!');
			}else{
				$result = array('state'=>false,'msg'=>'修改密码失败!');
			}
			return $result;
		}
	}
	
	//管理员删除
	public function adminDel($admin_id){
		if (!empty($admin_id)){
			if ($admin_id == 1){
				$result = array('state'=> false, 'msg'=>'超级管理员不能删除');
			}else{
				if (M('admin')->delete($admin_id)){
					$result = array('state'=> true);
				}else{
					$result = array('state'=> false);
				}
			}
			return $result;
		}
	}
	
	//验证管理员账号是否存在
	public function validate_admin($account){
		$account = M('admin')->where(array('account'=>$account))->getField('account');
		if (empty($account)){
			return true;
		}else {
			return false;
		}
	}

	//数据排行
	public function get_user_info($where) {
		$Model = M('seller');
		if(!empty($where['key'])) {
			$map['account'] = array('like', '%'.$where['key'].'%');
		}
		if(!empty($where['timestart']) && !empty($where['timeend'])) {
			$map['_string'] = 'add_time > '.strtotime($where['timestart']).' AND add_time < '.strtotime($where['timeend']);
		}
		$map = array_filter($map);
		$count = $Model->where($map)->count();
		$p = getpage($count,15);
		$list = $Model->where($map)->limit($p->firstRow, $p->listRows)->field('seller_id,account,money,level_id')->select();
		foreach ($list as $key => $value) {
			$list[$key]['task'] = M('task')->where(array('seller_id'=>$list[$key]['seller_id']))->count();
			$list[$key]['user_grade'] = M('seller_level')->where(array('level_id'=>$list[$key]['level_id']))->getField('reduction');
			$user_record = M('seller_recharge')->where(array('seller_id'=>$list[$key]['seller_id'], 'pay_state'=>2))->field('charge_money')->select();
			foreach ($user_record as $k => $v) {
				$list[$key]['user_record'] += $user_record[$k]['charge_money'];
			}
		}
		return array('list'=>$list, 'page'=>$p);
	}

	// 统计
	public function tongji() {
		$list['task'] = M('task')->count();
		$task = M('task')->field('unit_price')->select();
		foreach ($task as $key => $value) {
			$list['total_price'] += $task[$key]['unit_price'];
		}
		$list['unit_price'] = $list['total_price'] / $list['task'];
		$list['record'] = M('seller_recharge')->count();
		$list['user'] = M('seller')->count();
		$user_record = M('seller_recharge')->where(array('pay_state'=>2))->field('charge_money')->select();
		foreach ($user_record as $key => $value) {
			$list['user_record'] += $user_record[$key]['charge_money'];
		}
		return $list;
	}

	// 系统首页数据
	public function get_data() {
		//推广人数
		$data['seller_union'] = M('union_log')->count();
		//发布任务数
		$data['seller_task'] = M('task')->count();
		//任务总金额
		$data['task_price'] = M('task')->SUM('unit_price');
		//任务均价
		$data['task_equally'] = round($data['task_price'] / $data['seller_task'], 2);
		//会员充值总金额
		$data['seller_recharge'] = M('seller_recharge')->SUM('charge_money');
		//会员消费总金额
		$data['seller_consume'] = M('seller_finance')->where(array('change_type'=>2))->SUM('money');
		//会员可用总金额
		$data['seller_money'] = M('seller')->SUM('money');
		//文档列表数
		$data['article'] = M('article')->count();
		//注册会员数
		$data['seller_sum'] = M('seller')->count();
		//会员充值记录数
		$data['seller_recharge_sum'] = M('seller_recharge')->count();

		//一周任务统计

		$s_date[0] = date("Y-m-d", strtotime("-1 day"));
		$e_date[0] = date("Y-m-d");
		$task[0]['num'] = $this->task_sum($s_date[0], $e_date[0]);
		$task[0]['money'] = $this->recharge_sum($s_date[0], $e_date[0]);
		$task[0]['date'] = date('m-d');

		$s_date[1] = date("Y-m-d", strtotime("-2 day"));
		$e_date[1] = date("Y-m-d", strtotime("-1 day"));
		$task[1]['num'] = $this->task_sum($s_date[1], $e_date[1]);
		$task[1]['money'] = $this->recharge_sum($s_date[1], $e_date[1]);
		$task[1]['date'] = date('m-d', strtotime("-1 day"));

		$s_date[2] = date("Y-m-d", strtotime("-3 day"));
		$e_date[2] = date("Y-m-d", strtotime("-2 day"));
		$task[2]['num'] = $this->task_sum($s_date[2], $e_date[2]);
		$task[2]['money'] = $this->recharge_sum($s_date[2], $e_date[2]);
		$task[2]['date'] = date('m-d', strtotime("-2 day"));

		$s_date[3] = date("Y-m-d", strtotime("-4 day"));
		$e_date[3] = date("Y-m-d", strtotime("-3 day"));
		$task[3]['num'] = $this->task_sum($s_date[3], $e_date[3]);
		$task[3]['money'] = $this->recharge_sum($s_date[3], $e_date[3]);
		$task[3]['date'] = date('m-d', strtotime("-3 day"));

		$s_date[4] = date("Y-m-d", strtotime("-5 day"));
		$e_date[4] = date("Y-m-d", strtotime("-4 day"));
		$task[4]['num'] = $this->task_sum($s_date[4], $e_date[4]);
		$task[4]['money'] = $this->recharge_sum($s_date[4], $e_date[4]);
		$task[4]['date'] = date('m-d', strtotime("-4 day"));

		$s_date[5] = date("Y-m-d", strtotime("-6 day"));
		$e_date[5] = date("Y-m-d", strtotime("-5 day"));
		$task[5]['num'] = $this->task_sum($s_date[5], $e_date[5]);
		$task[5]['money'] = $this->recharge_sum($s_date[5], $e_date[5]);
		$task[5]['date'] = date('m-d', strtotime("-5 day"));

		$s_date[6] = date("Y-m-d", strtotime("-7 day"));
		$e_date[6] = date("Y-m-d", strtotime("-6 day"));
		$task[6]['num'] = $this->task_sum($s_date[6], $e_date[6]);
		$task[6]['money'] = $this->recharge_sum($s_date[6], $e_date[6]);
		$task[6]['date'] = date('m-d', strtotime("-6 day"));

		if(empty($data['seller_union'])) $data['seller_union'] = 0;
		if(empty($data['seller_task'])) $data['seller_task'] = 0;
		if(empty($data['task_price'])) $data['task_price'] = 0;
		if(empty($data['task_equally'])) $data['task_equally'] = 0;
		if(empty($data['seller_recharge'])) $data['seller_recharge'] = 0;
		if(empty($data['seller_consume'])) $data['seller_consume'] = 0;
		if(empty($data['seller_money'])) $data['seller_money'] = 0;
		if(empty($data['article'])) $data['article'] = 0;
		if(empty($data['seller_sum'])) $data['seller_sum'] = 0;
		if(empty($data['seller_recharge_sum'])) $data['seller_recharge_sum'] = 0;
		return array('data'=>$data, 'task'=>$task);
	}

	/*
	* 一周任务统计
	* s_date 开始时间，e_date 结束时间
	* 商品确认页
	*/
	public function task_sum($s_date, $e_date) {
		$Model = new \Think\Model();
		$num = $Model->query("SELECT COUNT(*) AS count FROM `kd_task` WHERE ( add_time BETWEEN '".$s_date."' AND '".$e_date."' )");
		return $num[0]['count'];
	}

	/*
	* 一周金额统计
	* s_date 开始时间，e_date 结束时间
	* 商品确认页
	*/
	public function recharge_sum($s_date, $e_date) {
		$Model = new \Think\Model();
		$sum = $Model->query("SELECT COALESCE(SUM(charge_money),0) AS sum FROM `kd_seller_recharge` WHERE ( charge_create BETWEEN '".$s_date."' AND '".$e_date."' )");
		
		return $sum[0]['sum'];
	}


	//友情链接列表
	public function get_fline_list() {
		$list = M('flink')->order('sort desc')->select();
		foreach ($list as $key => $value) {
			$list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
		}
		return $list;
	}

	//友情链接详情
	public function get_flink_detail($flink_id) {
		$list = M('flink')->where(array('flink_id'=>$flink_id))->find();
		return $list;
	}

	// 友情链接添加
	public function flinkAdd($data) {
		if(empty($data['name'])) return array('state'=>false, 'msg'=>'请输入链接名!');
		if(empty($data['link'])) return array('state'=>false, 'msg'=>'请输入链接地址!');
		if(empty($data['pic'])) return array('state'=>false, 'msg'=>'请输入链接图片!');
		if(empty($data['flink_id'])) {
			if(M('flink')->add($data)) {
				$result = array('state'=>true, 'msg'=>'提交成功!');
			} else {
				$result = array('state'=>false, 'msg'=>'提交失败!');
			}
		} else {
			if(M('flink')->save($data)) {
				$result = array('state'=>true, 'msg'=>'提交成功!');
			} else {
				$result = array('state'=>false, 'msg'=>'提交失败!');
			}
		}

		return $result;
	}

	// 友情链接删除
	public function flink_del($flink_id) {
		if(M('flink')->where(array('flink_id'=>$flink_id))->delete()) {
			$result = array('state'=>true, 'msg'=>'删除成功!');
		} else {
			$reuslt = array('state'=>false, 'msg'=>'删除失败!');
		}
		return $result;
	}

	//管理员编辑、添加
	public function admin_add($data) {
		$Model = M('admin');
		$admin_role = $Model->where(array('admin_id'=>session('admin_id')))->getField('role');
		if($admin_role == 0) {
			if(empty($data['account'])) return array('state'=>false, 'msg'=>'请输入管理员账号!');
			if(empty($data['password'])) return array('state'=>false, 'msg'=>'请输入管理员密码!');
			$data['password'] = encrypt($data['password']);
			$data['addtime'] = time();
			$data['login_ip'] = get_client_ip();
			if(!empty($data['admin_id'])) {
				if($Model->save($data)) {
					$result = array('state'=>true, 'msg'=>'修改成功!');
				} else {
					$result = array('state'=>true, 'msg'=>'修改失败!');
				}
			}else {
				$data['role'] = 1;
				if($Model->add($data)) {
					$result = array('state'=>true, 'msg'=>'提交成功!');
				} else {
					$result = array('state'=>true, 'msg'=>'提交失败!');
				}
			}
		}else {
			$result = array('state'=>false, 'msg'=>'无操作权限!');
		}

		return $result;
	}

	//管理员列表
	public function get_admin_list() {
		$Model = M('admin');
		$count = $Model->count();
		$p = getpage($count,15);
		$list = $Model->limit($p->firstRow, $p->listRows)->order('role asc')->select();
		foreach ($list as $key => $value) {
			$list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
		}
		return array('list'=>$list, 'page'=>$p);
	}

	//删除管理员
	public function admin_del($admin_id) {
		$Model = M('admin');
		$admin_role = $Model->where(array('admin_id'=>session('admin_id')))->getField('role');
		if($admin_role == 0) {
			if($Model->where(array('admin_id'=>$admin_id))->delete()) {
				$result = array('state'=>true, 'msg'=>'删除成功!');
			}else {
				$result = array('state'=>false, 'msg'=>'删除失败!');
			}
		}else {
			$result = array('state'=>false, 'msg'=>'无权限删除!');
		}
		return $result;
	}

	//系统参数添加、编辑
	public function system_add($data) {
		if(!empty($data['system_id'])) {
			if(M('system')->save($data)) {
				$result = array('state'=>true, 'msg'=>'修改成功!');
			}else {
				$result = array('state'=>true, 'msg'=>'修改失败!');
			}
		}else {
			if(M('system')->add($data)) {
				$result = array('state'=>true, 'msg'=>'添加成功!');
			}else {
				$result = array('state'=>true, 'msg'=>'添加失败!');
			}
		}
		return $result;
	}

	//获取系统参数
	public function system_detail() {
		$data = M('system')->find();
		return $data;
	}
}
?>