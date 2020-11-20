<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class SellerController extends BaseController {
	
	//用户中心
	public function index(){
		$this->display();
	}
	
	//推广赚钱
	public function union(){
		$Model = M('seller');
		$bonus = $Model->where(array('seller_id'=>$this->seller_id))->getField('bonus');
		$spread = $Model->where(array('seller_id'=>$this->seller_id))->getField('spread');
		if ($spread == 2 || $spread == 3) {
			$son_seller = $Model->where(array('parent_id'=>$this->seller_id))->field('seller_id,account,add_time')->select();
			if(!empty($son_seller)){
				if ($spread == 2){
					foreach ($son_seller as $key => $value) {
						$son_seller[$key]['task'] = M('union_log')->where(array('seller_id'=>$son_seller[$key]['seller_id']))->count();
						$son_seller[$key]['bonus'] = M('union_log')->where(array('seller_id'=>$son_seller[$key]['seller_id']))->SUM('bonus');
						$son_seller[$key]['add_time'] = date('Y-m-d H:i:s', $son_seller[$key]['add_time']);
					}
				}elseif ($spread == 3){
					foreach ($son_seller as $key => $value) {
						$son_seller[$key]['task'] = M('seller_spread_log')->where(array('seller_id'=>$son_seller[$key]['seller_id']))->count();
						if ($son_seller[$key]['task'] == 0){
							$son_seller[$key]['bonus'] = '0.00';
						}else {
							$son_seller[$key]['bonus'] = '200.00';
						}
						$son_seller[$key]['add_time'] = date('Y-m-d H:i:s', $son_seller[$key]['add_time']);
					}
				}
				
			}
			foreach ($son_seller as $key=>$value){
				$son_seller[$key]['account'] = substr_replace($son_seller[$key]['account'],'****',3,4);
			}
			$this->assign('bonus', $bonus);
			$this->assign('list', $son_seller);
			
		} else {
			$this->assign('msg', '未获得推广权限！');
		}
	

		$this->display();
	}
	
	//推广赚钱--详细
	public function sonUnion(){
		$Model = M('seller');
		$bonus = $Model->where(array('seller_id'=>$this->seller_id))->getField('bonus');
		$spread = $Model->where(array('seller_id'=>$this->seller_id))->getField('spread');
		if ($spread == 2) {
			$seller_id = I('get.seller_id', '', true);
			$account = $Model->where(array('seller_id'=>$seller_id))->getField('account');
			if (!empty($seller_id)) {
				$list = M('union_log')->where(array('seller_id'=>$seller_id))->select();
				foreach ($list as $key => $value) {
					$list[$key]['account'] = $account;
					$list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
				}
			}
			$this->assign('bonus', $bonus);
			$this->assign('list', $list);
			
		} else {
			$this->assign('msg', '未获得推广权限！');
		}
		$this->display();
	}

	//删除记录
	public function logDel() {
		$f_id = I('post.f_id', '', true);
		if(empty($f_id)) {
			exit(json_encode(array('state'=>false, 'msg'=>'未指定记录')));
		}
		if(M('seller_finance')->where(array('f_id'=>$f_id))->delete()){
			exit(json_encode(array('state'=>true, 'msg'=>'删除成功')));
		}else {
			exit(json_encode(array('state'=>false, 'msg'=>'删除失败')));
		}
	}
	
	//消费记录
	public function accountlog(){
		$tid = I('get.tid');
		$where['change_type'] = empty($tid) ? '' : $tid;
		$where = array_filter($where);
		$where['seller_id'] = $this->seller_id;
		$Model = M('seller_finance');
		$count = $Model->where($where)->count();
        $p = getpage($count,15);
		$list = $Model->where($where)->order('change_time desc')->select();
//		foreach ($list as $key => $value) {
//			$list[$key]['change_time'] = date('Y-m-d H:i:s', $list[$key]['change_time']);
//		}
		$this->assign('tid', $where['change_type']);
		$this->assign('page', amazeui_page_style($p->show()));
		$this->assign('list', $list);
		$this->display();
	}
	
	//充值记录
	public function record(){
		$count = M('seller_recharge')->where(array('seller_id'=>$this->seller_id))->count();
        $p = getpage($count,15);
		$list = M('seller_recharge')->where(array('seller_id'=>$this->seller_id))->limit($p->firstRow, $p->listRows)->order('charge_id desc')->select();
		foreach ($list as $key => $value) {
			switch ($list[$key]['pay_type']) {
				case 1:
					$list[$key]['mode'] = '支付宝支付';
					break;
				
				case 2:
					$list[$key]['mode'] = '微信支付';
					break;
				
				default:
					$list[$key]['mode'] = '银联支付';
					break;
			}
			if (!empty($list[$key]['pay_time'])) {
				$list[$key]['pay_time'] = date('Y-m-d H:i:s', $list[$key]['pay_time']);
			}
		}		
		
		$this->assign('page', amazeui_page_style($p->show()));
		$this->assign('list', $list);
		$this->display();
	}
	
	//支付回调
	public function called() {
		$charge_id = I('post.charge_id', '', true);
		if (!empty($charge_id)) {
			if (M('seller_record')->where(array('charge_id'=>$charge_id))->save(array('pay_state'=>2,'pay_time'=>date('Y-m-d H:i:s')))) {
				$result = array('state'=>true,'msg'=>'支付成功!');
			} else {
				$result = array('state'=>true,'msg'=>'支付失败!');
			}
			exit(json_encode($result));
		}
	}

	//修改密码
	public function password(){
		if (!empty($_POST)){
			$password = I('post.password', '', true);
			$new_password = I('post.new_password', '', true);
			$confirm_password = I('post.confirm_password', '', true);
			if (empty($password)) exit(json_encode(array('state'=>false,'msg'=>'请输入原来密码')));
			if (empty($new_password)) exit(json_encode(array('state'=>false,'msg'=>'请输入新密码')));
			if (empty($confirm_password)) exit(json_encode(array('state'=>false,'msg'=>'请重复新密码')));
			if ($new_password != $confirm_password) exit(json_encode(array('state'=>false,'msg'=>'两次密码不同')));
			if (encrypt($password) != $this->seller['password']) exit(json_encode(array('state'=>false,'msg'=>'原密码错误')));
			$data = array(
					'seller_id'    => $this->seller_id,
					'password'   => encrypt($new_password),
			);
			if (M('seller')->save($data)){
				setcookie('account','',time()-3600,'/');
				setcookie('seller_id','',time()-3600,'/');
				session_unset();
				session_destroy();
				exit(json_encode(array('state'=>true,'msg'=>'修改密码成功,请重新登录')));
			}else{
				exit(json_encode(array('state'=>false,'msg'=>'修改密码失败')));
			}
		}
		
		$this->display();
	}
	
	//修改资料
	public function account(){
		$data = I('post.');
		if (!empty($data)){
			$data['seller_id'] = $this->seller_id;
			if (M('seller')->save($data)){
				exit(json_encode(array('state'=>true,'msg'=>'修改成功')));
			}else{
				exit(json_encode(array('state'=>false,'msg'=>'修改失败')));
			}
		}
		$this->display();
	}
	
	//用户地址
	public function address(){
		$Model = M('seller_address');
		$id = I('get.id');
		if(!empty($id)) {
			$list = $Model->where(array('id'=>$id))->find();
			$this->assign('seller_list', $list);
		}
	
		$addressList = $Model->where(array('seller_id'=>$this->seller_id))->order('status desc')->select();
		if (!empty($addressList)){
			foreach ($addressList as $key=>$value){
				$addressList[$key]['status_name'] = $addressList[$key]['status'] == 1 ? '正常' : '异常';
			}
		}

		$data = I('post.');		
	
		if (!empty($data)){
			if (empty($data['wangwang'])) exit(json_encode(array('state'=>false,'msg'=>'请输入旺旺店铺名')));
			if (empty($data['addresser'])) exit(json_encode(array('state'=>false,'msg'=>'请输入发件人')));
			if (empty($data['mobile'])) exit(json_encode(array('state'=>false,'msg'=>'请输入手机号码')));
			if (empty($data['province'])) exit(json_encode(array('state'=>false,'msg'=>'请选择省份')));
			if (empty($data['city'])) exit(json_encode(array('state'=>false,'msg'=>'请选择城市')));
			if (empty($data['district'])) exit(json_encode(array('state'=>false,'msg'=>'请选择地区')));
			if (empty($data['address'])) exit(json_encode(array('state'=>false,'msg'=>'请输入详细地址')));
			$data['seller_id'] = $this->seller_id;
			$data['addtime'] = time();
			if(!empty($data['id'])) {
				if ($Model->save($data)) {
					exit(json_encode(array('state'=>true,'msg'=>'添加成功')));
				}else{
					exit(json_encode(array('state'=>false,'msg'=>'添加失败')));
				}
			} else {
				if ($Model->add($data)) {
					exit(json_encode(array('state'=>true,'msg'=>'添加成功')));
				}else{
					exit(json_encode(array('state'=>false,'msg'=>'添加失败')));
				}
			}

		}
		$this->assign('list', $addressList);
		$this->display();
	}

	//用户默认地址
	public function defaultAddress() {
		$id = I('post.id', '', true);
		if(empty($id)) exit(json_encode(array('state'=>false, 'msg'=>'未指定地址')));
		M('seller_address')->where(array('status'=>3))->save(array('status'=>1));
		if(M('seller_address')->where(array('id'=>$id))->save(array('status'=>3))) {
			exit(json_encode(array('state'=>true, 'msg'=>'成功默认地址')));
		}else {
			exit(json_encode(array('state'=>false, 'msg'=>'失败默认地址')));
		}
	}

	//用户地址删除
	public function addressDel() {
		$id = I('post.id', '', true);
		if(empty($id)) exit(json_encode(array('state'=>false, 'msg'=>'未指定地址')));
		if(M('seller_address')->where(array('id'=>$id))->delete()) {
			exit(json_encode(array('state'=>true, 'msg'=>'删除成功')));
		}else {
			exit(json_encode(array('state'=>false, 'msg'=>'删除失败')));
		}
	}
	
	//退出登陆
	public function logout(){
		setcookie('account','',time()-3600,'/');
		setcookie('seller_id','',time()-3600,'/');
		session_unset();
		session_destroy();
		header("location:".U('Home/Seller/login'));
		exit;
	}
	
	//用户注册
	public function register() {
		if (!empty($_POST)){
			$account = I('post.account', '', true);
			$password = I('post.password', '', true);
			$confirm_password = I('post.confirm_password', '', true);
			$QQ = I('post.QQ', '', true);
			$code = I('post.code', '', true);
			$t = I('post.t', '', true);
			if (empty($account)) exit(json_encode(array('state'=>false,'msg'=>'请请输入实名制手机号码')));
			$is_mobile = $this->is_mobile($account);
			if (!$is_mobile){
				exit(json_encode(array('state'=>false,'msg'=>'手机格式不正确')));
			}
			if (empty($password)) exit(json_encode(array('state'=>false,'msg'=>'请设置密码')));
			if (empty($confirm_password)) exit(json_encode(array('state'=>false,'msg'=>'请输入重复密码')));
			if (empty($QQ)) exit(json_encode(array('state'=>false,'msg'=>'请填写QQ号')));
			if (empty($code)) exit(json_encode(array('state'=>false,'msg'=>'请填写验证码')));
			if ($password != $confirm_password) exit(json_encode(array('state'=>false,'msg'=>'两次密码不相同')));
			$is_account = M('seller')->where(array('account'=>$account))->getField('account');
			if (!empty($is_account)) exit(json_encode(array('state'=>false,'msg'=>'账号已存在,请更换')));
			$verify = new \Think\Verify();
			$code_check = $verify->check($code, $id = '');
			if ($code_check != true){
				exit(json_encode(array('state'=>false,'msg'=>'验证码错误')));
			}
			$register_money = M('system')->where(array('system_id'=>1))->getField('register_money');
			$data = array(
					'account'   	=> $account,
					'password'  	=> encrypt($password),
					'QQ'        	=> $QQ,
					'add_time'   	=> time(),
					'money'			=> $register_money,
					'left_points'	=> $register_money,
			);
			if (!empty($t)){
				$seller_id = M('seller')->where(array('account'=>$t, 'spread'=>array('gt',1)))->getField('seller_id');
				if (!empty($seller_id)){
					$data['parent_id'] = $seller_id;
				} 
			}
			$seller_id = M('seller')->add($data);
			if ($seller_id){
// 				if(!empty($register_money)) {
// 					$log = array(
// 						'seller_id'  	=> $seller_id,
//                         'change_time'	=>	date("Y-m-d H:i:s",time()),
// 						'points'   		=> $register_money,
// 						'money'			=> $register_money,
// 						'money_next'	=> $register_money,
// 						'change_desc'	=> '注册送金额',
// 					);
// 					M('seller_finance')->add($log);
// 				}

				if (!empty($t)){
					$res = array(
							'seller_id'     => $seller_id,
							'parent_id'     => $data['parent_id'],
							'add_time'      => date('Y-m-d H:i:s')
					);
					M('union_log')->add($res);
				}
				
				
				exit(json_encode(array('state'=>true,'msg'=>'注册成功')));
			}else{
				exit(json_encode(array('state'=>false,'msg'=>'注册失败,请稍后再试')));
			}
		}
		$seller = session('seller');
		if (!empty($seller)) {
			echo "<script>alert('您已登录')</script>";
			echo "<script>window.location.href='/'</script>";
		}
		$t = session('t');
		$this->assign('t', $t);
		$this->display();
	}
	
	//用户登陆
	public function login(){
		if (!empty($_POST)){
			$account = I('post.account', '', true);
			$password = I('post.password', '', true);
			if (empty($account)) exit(json_encode(array('state'=>false,'msg'=>'请请输入实名制手机号码')));
			if (empty($password)) exit(json_encode(array('state'=>false,'msg'=>'请设置密码')));
			$seller = M('seller')->where(array('account'=>$account, 'password'=>encrypt($password)))->find();
			if (empty($seller)) exit(json_encode(array('state'=>false,'msg'=>'账号密码错误')));
			$row = array(
					'seller_id'         => $seller['seller_id'],
					'last_login_time'   => time(),
					'last_login_ip'     => $_SERVER['REMOTE_ADDR'],
			);
			if (M('seller')->save($row)){
				session('seller_id', $seller['seller_id']);
				setcookie('seller_id', $seller['seller_id'],null,'/');
				setcookie('account', $seller['account'],null,'/');
				exit(json_encode(array('state'=>true,'msg'=>'登录成功', 'data'=>$seller)));
			}else{
				exit(json_encode(array('state'=>false,'msg'=>'登录异常,请稍后再试')));
			}
		}
		$seller = session('seller');
		if (!empty($seller)) {
			echo "<script>alert('您已登录')</script>";
			echo "<script>window.location.href='/'</script>";
		}
		$this->display();
	}
	
	//验证手机号是否正确
	public function is_mobile($mobile){
		if(preg_match("/^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/",$mobile)){
			return true;
		}else{
			return false;
		}
	}
	
	//验证码
	public function verify()
	{
		$config = array(
				'fontSize'  =>  14,
				'length'    =>  4,
				'imageH'    =>  30,
				'imageW'    =>  80,
				'useCurve'  =>  true,
				'useNoise'  =>  false,
				'codeSet'   =>  '0123456789',
		);
		$Verify = new Verify($config);
		$Verify->entry();
	}

    public function  article()
    {
        $article_id = I('post.articel_id','','intval');
        $where['article_id'] = $article_id;
        $r = M('article')->where($where)->find();
        if(empty($r)){
            $msg['code'] = 2;
            $msg['msgs'] = '文字不存在！';
        }
        else{
            $msg['code'] = 1;
            $msg['msgs'] = $r['content'];
        }
       // exit(json_encode($msg));
        $this->ajaxReturn($msg);
    }
}