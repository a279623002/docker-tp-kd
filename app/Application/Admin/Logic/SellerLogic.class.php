<?php
namespace Admin\Logic;
use Think\Model\RelationModel;
class SellerLogic extends RelationModel {

    //用户列表
    public function get_user_list($where) {
        $Model = M('seller');
        $Model_grade = M('seller_level');
        if(!empty($where['grade'])) {
            $map['level_id'] = $where['grade'];
        }
        if(!empty($where['account'])) {
            $map['account'] = array('like', '%'.$where['account'].'%');
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $user_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('add_time desc')->select();
        foreach ($user_list as $key => $value) {
            $user_list[$key]['user_grade'] = $Model_grade->where(array('level_id'=>$user_list[$key]['level_id']))->find();
            $user_list[$key]['addtime'] = date('Y-m-d H:i:s', $user_list[$key]['add_time']);
            $user_list[$key]['last_login'] = date('Y-m-d H:i:s', $user_list[$key]['last_login_time']);
        }
    
        return array('list'=>$user_list, 'page'=>$p);
    }

    //用户密码修改
    public function changePw($data) {
        $Model = M('seller');
        $data['password'] = encrypt($data['new_password']);
        if ($Model->save($data)){
            $result = array('state'=>true,'msg'=>'修改密码成功! 该用户新密码为：'.$data['new_password']);
        }else{
            $result = array('state'=>false,'msg'=>'修改密码失败!');
        }
        return $result;
    }

    //用户冻结
    public function freeze($data) {
        $Model = M('seller');
        if ($Model->where(array('user_id'=>$data['user_id']))->save(array('status'=>$data['status']))){
            $result = array('state'=>true,'msg'=>'操作成功!');
        }else{
            $result = array('state'=>false,'msg'=>'操作失败!');
        }
        return $result;
    }

    //添加用户
    public function add_user($data) {
        $Model = M('seller');
        $Model_grade = M('seller_level');
        $point = $Model_grade->where(array('seller_id'=>$data['seller_id']))->getField('growth_from, growth_end');
        if($data['left_growth'] < $point['growth_from'] && $data['left_growth'] > $point['growth_end']) {
            $result = array('state'=>false, 'msg'=>'积分不在范围内!');
        } else {
            if (!empty($data['seller_id'])) {
            	$seller = $this->get_user_detail($data['seller_id']);
            	if ($seller['money'] != $data['money']){
            		if ($seller['money'] > $data['money']){
            			$res = array(
            					'seller_id'		=>	$data['seller_id'],
            					'money'			=>	$seller['money'] - $data['money'],
            					'money_next'			=>	$data['money'],
            					'money_prev'			=>	$seller['money'],
            					'change_time'	=>	date("Y-m-d H:i:s",time()),
            					'change_desc'	=>	'后台修改',
            					'change_type'	=>	99
            			);
            			M('seller_finance')->add($res);
            				
            		}else{
            			$res = array(
            					'seller_id'		=>	$data['seller_id'],
            					'money'			=>	$data['money'] - $seller['money'],
            					'money_next'			=>	$data['money'],
            					'money_prev'			=>	$seller['money'],
            					'change_time'	=>	date("Y-m-d H:i:s",time()),
            					'change_desc'	=>	'后台修改',
            					'change_type'	=>	99
            			);
            			M('seller_finance')->add($res);
            		}
            	}
                if($user = $Model->save($data)) {
                    $result = array('state'=>true, 'msg'=>'添加成功');
                } else {
                    $result = array('state'=>false, 'msg'=>'添加失败');
                }
         
            } else {
                $data['addtime'] = time();
                $data['password'] = encrypt($data['password']);
                if ($user = $Model->add($data)) {
                    $result = array('state'=>true, 'msg'=>'添加成功');
                } else {
                    $result = array('state'=>false, 'msg'=>'添加失败');
                }
            }
        }


        return $result;
    }

    //获取用户详情
    public function get_user_detail($seller_id) {
        $detail = M('seller')->where(array('seller_id'=>$seller_id))->find();
        $detail['user_grade'] = M('seller_level')->where(array('seller_id'=>$seller_id))->find();
        return $detail;
    }

    // 用户金额记录
    public function get_log_list($seller_id) {
        $Model = M('seller_finance');
		$list = $Model->where(array('seller_id'=>$seller_id))->order('change_time desc')->select();
        return $list;
    }

    //用户发货地址
    public function get_user_address($key) {
        $Model = M('user_address');
        if(!empty($key)) {
            $where['address']  = array('like', '%'.$key.'%');
            $where['mobile']  = array('like','%'.$key.'%');
            $where['_logic'] = 'or';
        }
        $where = array_filter($where);
        $count = $Model->where($where)->count();
		$p = getpage($count,15);
        $list = $Model->where($where)->limit($p->firstRow, $p->listRows)->order('addtime desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
            $list[$key]['user'] = M('user')->where(array('user_id'=>$list[$key]['user_id']))->field('account, status')->find();
        }
        return array('list'=>$list, 'page'=>$p);
    }

    //删除用户发货地址
    public function del_address($id) {
        if(M('user_address')->where(array('id'=>$id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        } else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    //充值记录列表
    public function get_record_list($where) {
        $Model = M('seller_recharge');
        if(!empty($where['pay_state'])) {
            $map['back_true'] = $where['pay_state'];
        }
        if(!empty($where['key'])) {
            $map['charge_number'] = array('like', '%'.$where['key'].'%');
            $map['back_tradeno'] = array('like', '%'.$where['key'].'%');
            $map['charge_account'] = array('like', '%'.$where['key'].'%');
            $map['_logic'] = 'or';
        }
        if(!empty($where['timestart']) && !empty($where['timeend'])) {
            $map['_string'] = 'charge_create > '.strtotime($where['timestart']).' AND charge_create < '.strtotime($where['timeend']);
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $record_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('charge_create desc')->select();
        foreach ($record_list as $key => $value) {
            $record_list[$key]['account'] = M('seller')->where(array('seller_id'=>$record_list[$key]['seller']))->getField('account');
        }
        return array('list'=>$record_list, 'page'=>$p);
    }

    //充值记录删除
    public function record_del($charge_id) {
        if(M('seller_recharge')->where(array('charge_id'=>$charge_id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        }else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    //充值记录详情
    public function get_record($charge_id) {
        $data = M('seller_recharge')->where(array('charge_id'=>$charge_id))->find();
        $data['account'] = M('seller')->where(array('seller'=>$data['seller']))->getField('account');
        return $data;
    }

    //会员支付
    public function recharge_record($where) {
        if(empty($where['charge_id'])) return array('state'=>false, 'msg'=>'未指定记录');
        if(empty($where['seller_id'])) return array('state'=>false, 'msg'=>'未指定用户');
        if(empty($where['back_tradeno'])) return array('state'=>false, 'msg'=>'未填写支付交易号');
        if(empty($where['paypwd'])) return array('state'=>false, 'msg'=>'未填写支付密码');
        $admin_pw = M('Admin')->where(array('account'=>$where['account']))->getField('password');
        if($admin_pw != encrypt($where['paypwd'])) return array('state'=>false, 'msg'=>'密码错误');
        $where['back_true'] = 2;
        $where['charge_create'] = date('Y-m-d H:i;s');
        $where['charge_account'] = $where['account'];
        if(M('seller_recharge')->save($where)) {
            $t_seller =  M('seller')->where(array('seller_id'=>$where['seller_id']))->find();
//            $user_money = M('seller')->where(array('seller_id'=>$where['seller_id']))->getField('money');
//            $left_points = M('seller')->where(array('seller_id'=>$where['seller_id']))->getField('left_points');
            $user_money = $t_seller['money'];
            $left_points = $t_seller['left_points'];

            $user_money += $where['charge_money'];
            $left_points += $where['charge_money'];

            $result = M('seller')->where(array('seller_id'=>$where['seller_id']))->save(array('money'=>$user_money,'left_points'=>$left_points));
            if(empty($result)) return array('state'=>false, 'msg'=>'充值失败!');
            return array('state'=>true, 'msg'=>'支付成功!');
        }else {
            return array('state'=>false, 'msg'=>'支付失败!');
        }
    }

    //推广记录
	public function get_user_union() {
        $Model = M('union_log');
        
        $count = $Model->count();

        $p = getpage($count,15);
        $list = $Model->limit($p->firstRow, $p->listRows)->order('add_time desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['parent_account'] = M('Seller')->where(array('Seller_id'=>$list[$key]['parent_id']))->getField('account');
            $list[$key]['account'] = M('Seller')->where(array('Seller_id'=>$list[$key]['Seller_id']))->getField('account');
           // $list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['add_time']);
            $list[$key]['addtime'] =  $list[$key]['add_time'];
        }
        $sum['count'] = $count;
        $sum['count_bonus'] = $Model->SUM('bonus');
        return array('list'=>$list, 'page'=>$p, 'sum'=>$sum);
    }

    //会员积分等级比例列表
    public function get_grade_list() {
        $list = M('seller_level')->select();
        return $list;
    }

    //会员积分等级比例详情
    public function grade_edit($level_id) {
        $result = M('seller_level')->where(array('level_id'=>$level_id))->find();
        return $result;
    }

    //会员积分等级比例修改
    public function grade_update($data) {
        if(empty($data['level_id'])) return array('state'=>false, 'msg'=>'未指定等级!');
        if(M('seller_level')->save($data)) {
            return array('state'=>true, 'msg'=>'修改成功!');
        }else {
            return array('state'=>true, 'msg'=>'修改失败!');
        }
    }

    //消费记录
    public function get_user_score($where) {
        $Model = M('seller_finance');
        if(!empty($where['key'])) {
            $where['account'] = array('like', '%'.$where['key'].'%');
            $map['seller_id'] = M('seller')->where($where)->getField('seller_id');
            $map['task_id'] =  array('like', '%'.$where['key'].'%');
        }
        if(!empty($where['change_type'])) {
            $map['change_type'] = $where['change_type'];
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $score_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('f_id desc')->select();
        foreach ($score_list as $key => $value) {
            $score_list[$key]['account'] = M('seller')->where(array('seller_id'=>$score_list[$key]['seller_id']))->getField('account');
            //$score_list[$key]['change_time'] = date('Y-m-d H:i:s', $score_list[$key]['change_time']);
        }
        return array('list'=>$score_list, 'page'=>$p);
    }

    //删除消费记录
    public function del_user_score($f_id) {
        if(M('seller_finance')->where(array('f_id'=>$f_id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        }else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    //空包记录
    public function get_task($where) {
        $Model = M('task');
        if(!empty($where['key'])) {
            $map['order_no'] = array('like', '%'.$where['key'].'%');
            // $map['aim_address'] = array('like', '%'.$where['key'].'%');
            // $map['_logic'] = 'or';
        }
        if(!empty($where['timestart']) && !empty($where['timeend'])) {
            $map['_string'] = 'add_time > '.$where['timestart'].' AND add_time < '.$where['timeend'];
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $score_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('add_time desc')->select();
        foreach ($score_list as $key => $value) {
            $score_list[$key]['account'] = M('seller')->where(array('seller_id'=>$score_list[$key]['seller_id']))->getField('account');
            // $score_list[$key]['order_time'] = date('Y-m-d H:i:s', $score_list[$key]['order_time']);
            // $score_list[$key]['addressed'] = explode('，', $score_list[$key]['aim_address']);
        }
        return array('list'=>$score_list, 'page'=>$p);
    }

    //发货任务确认
    public function task_confirm($task_id) {
        $Model = M('seller');
        $seller_id = M('task')->where(array('task_id'=>$task_id))->getField('seller_id');
        $unit_price = M('task')->where(array('task_id'=>$task_id))->getField('unit_price');
        $seller = $Model->where(array('seller_id'=>$seller_id))->find();
        if($unit_price < $seller['money']) {
            $data = array(
                'seller_id'  => $seller_id,
                'money'    => $seller['money'] - $unit_price
            );
            $task = M('task')->where(array('task_id'=>$task_id))->save(array('send_state'=>2));
            if($task) {
   
                $Model->save($data);
                
                $first_seller = $Model->where(array('seller_id'=>$seller['parent_id']))->find();
                if ($first_seller) {
                    $first_level = M('system')->getField('first_level');
                    $first_bonus = $first_seller['bonus'] + ($unit_price * $first_level);
                    $Model->where(array('seller_id'=>$first_seller['seller_id']))->save(array('bonus'=>$first_bonus));
                    $arr1 = array(
                        'seller_id'       => $seller_id,
                        'parent_id'     => $first_seller['seller_id'],
                        'bonus'         => $unit_price * $first_level,
                        'task_id'       => $task_id,
                        'addtime'       => time()
                    );
                    M('union_log')->add($arr1);
                    $second_seller = $Model->where(array('seller_id'=>$first_seller['parent_id']))->find();
                    if ($second_seller) {
                        $second_level = M('system')->getField('second_level');
                        $second_bonus = $second_seller['bonus'] + ($unit_price * $second_level);
                        $Model->where(array('seller_id'=>$second_seller['seller_id']))->save(array('bonus'=>$second_bonus));
                        $arr2 = array(
                            'seller_id'     => $first_seller['seller_id'],
                            'parent_id'     => $second_seller['seller_id'],
                            'bonus'         => $unit_price * $second_level,
                            'task_id'       => $task_id,
                            'addtime'       => time()
                        );
                        M('union_log')->add($arr2);
                    }
                }
                $result = array('state'=>true, 'msg'=>'发货成功!');
            }else {
                $result = array('state'=>false, 'msg'=>'发货失败!');
            }
        }else {
            $result = array('state'=>false, 'msg'=>'该用户余额不足!');
        }

        return $result;
    }

    //发货任务删除
    public function task_del($task_id) {
        if(M('task')->where(array('task_id'=>$task_id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        }else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    
    //商店列表
    public function get_shop_list($where) {
        $Model = M('seller_store');
        if(!empty($where['key'])) {
            $map['store_nick'] = array('like', '%'.$where['key'].'%');
        }
        if(!empty($where['status'])) {
            $map['status'] = $where['status'];
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $shop_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('add_time desc')->select();
        foreach ($shop_list as $key => $value) {
            $shop_list[$key]['username'] = M('seller')->where(array('seller_id'=>$shop_list[$key]['seller_id']))->getField('account');
        }
    
        return array('list'=>$shop_list, 'page'=>$p);
    }

    //商店审核
	public function auditing($data) {
        $Model = M('seller_store');
        if(empty($data['store_id'])) return array('state'=>false, 'msg'=>'未指定商店!');
        if(empty($data['status'])) return array('state'=>false, 'msg'=>'无操作!');
        if($Model->save($data)) {
            $result = array('state'=>true, 'msg'=>'操作成功!');
        }else {
            $result = array('state'=>false, 'msg'=>'操作失败!');
        }
        return $result;
    }
    
        
    //续费价格列表
    public function get_renew_list() {
        $Model = M('user_renew');
        $count = $Model->count();
        $p = getpage($count,15);
        $shop_list = $Model->limit($p->firstRow, $p->listRows)->order('month asc')->select();
        return array('list'=>$shop_list, 'page'=>$p);
    }

    //续费价格详情
    public function renew_detail($renew_id) {
        $Model = M('user_renew');
        $result = $Model->where(array('renew_id'=>$renew_id))->find();
        return $result;
    }

    //续费价格添加、编辑
    public function renew_add($data) {
        $Model = M('user_renew');
        if (!empty($data['renew_id'])) {
            if($Model->save($data)) {
                $result = array('state'=>true, 'msg'=>'修改成功!');
            } else {
                $result = array('state'=>false, 'msg'=>'修改失败!');
            }
        } else {
            if($Model->add($data)) {
                $result = array('state'=>true, 'msg'=>'添加成功!');
            } else {
                $result = array('state'=>false, 'msg'=>'添加失败!');
            }
        }
        return $result;
    }

    //续费价格删除
    public function renew_del($renew_id) {
        $Model = M('user_renew');
        if($Model->where(array('renew_id'=>$renew_id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        } else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    //店铺详情
    public function get_shop_detail($store_id) {
        $detail = M('seller_store')->where(array('store_id'=>$store_id))->find();
        $detail['account'] = M('seller')->where(array('seller_id'=>$detail['seller_id']))->getField('account');
        return $detail;
    }

    //编辑店铺
    public function shop_edit($data) {
        if(empty($data['store_nick'])) return array('state'=>false, 'msg'=>'请填写店铺名!');
        if(empty($data['manager'])) return array('state'=>false, 'msg'=>'请填写掌柜号!');
        if(empty($data['store_type'])) return array('state'=>false, 'msg'=>'请选择平台!');
        if(empty($data['property'])) return array('state'=>false, 'msg'=>'请选择店铺性质!');
        if(empty($data['name'])) return array('state'=>false, 'msg'=>'请填写发货人!');
        if(empty($data['mobile'])) return array('state'=>false, 'msg'=>'请填写发货电话!');
        if(empty($data['province'])) return array('state'=>false, 'msg'=>'请选择省份!');
        if(empty($data['city'])) return array('state'=>false, 'msg'=>'请选择城市!');
        if(empty($data['district'])) return array('state'=>false, 'msg'=>'请选择区域!');

        $where_list['store_nick'] = $data['store_nick'];
        $where_list['store_id'] = array('neq',$data['store_id']);
        $store_list =  M('seller_store')->where($where_list)->select();
        $result = '';
        if(!empty($store_list))
        {
            $result = array('state' => false, 'msg' => '该店铺'.$data['store_nick'].'已经存在!');
        }
        else {
            if (M('seller_store')->save($data)) {
                $result = array('state' => true, 'msg' => '修改成功!');
            } else {
                $result = array('state' => false, 'msg' => '修改失败!');
            }
        }
        return $result;
    }
}