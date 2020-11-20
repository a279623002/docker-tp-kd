<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends BaseController {

    function create_message_code() {
        $decode =array_merge(range(0,9),range(0,9));
        shuffle($decode);
        $code = implode('',array_slice($decode,0,6));
        return $code;
    }

	//余额充值
	public function pay(){
		$data = I('post.', '', true);
		if(!empty($data)) {
			if (empty($data['payAmount'])) exit(json_encode(array('state'=>false,'msg'=>'请填写金额')));
			if (empty($data['code'])) exit(json_encode(array('state'=>false,'msg'=>'请填写验证码')));
			$verify = new \Think\Verify();
			$code_check = $verify->check($data['code'], $id = '');
			if ($code_check != true){
				exit(json_encode(array('state'=>false,'msg'=>'验证码错误')));
			}
			// $add = array(
			// 	'optEmail'		=> 'yiwangbao@163.com',
			// 	'payAmount'			=> $data['payAmount'],
			// 	'title'			=> ''
				
			// );
			$add = array(
				'seller_id'		=> $this->seller_id,
				'charge_money'	=> $data['payAmount'],
                'charge_descript'	=> $this->create_message_code(),
                'charge_account'	=> "yiwangbao999@163.com",
				'charge_type'		=> 1,
				'back_true'		=> 1,
				'charge_create'	=> date('Y-m-d H:i:s')
			);
			$charge_id = M('seller_recharge')->add($add);
			if ($charge_id) {
				exit(json_encode(array('state'=>true, 'msg'=>'提交成功', 'charge_id'=>$charge_id)));
			} else {
				exit(json_encode(array('state'=>false, 'msg'=>'提交失败')));
			}
		}
		$this->display();
	}

	//生成订单表
	public function make() {
		$charge_id = I('get.charge_id', '', true);
		if(!empty($charge_id)) {
			$data = M('seller_recharge')->where(array('charge_id'=>$charge_id))->find();
			if(!empty($data)) {
				$this->assign('data', $data);
			}else {
				echo "<script>history.go(-1);</script>";
			}
		}else {
			echo "<script>history.go(-1);</script>";
		}
		$this->display();
	}

    //手动充值提交：
    public  function hand()
    {
        $msg['code'] = 0;
        $TradeNo = I('post.TradeNo','','trim');
        $SellerID = $this->seller_id;
        $IncomeMoney = I('post.IncomeMoney','','trim');
        if(empty($TradeNo)){
            $msg['msgs'] = '请输入流水号';
            $this->ajaxReturn($msg);
        }
        if(empty($IncomeMoney)){
            $msg['msgs'] = '请输入充值金额';
            $this->ajaxReturn($msg);
        }

        $find = M('seller_recharge_back')->where(array('back_tradeno'=>$TradeNo))->find();
        if(empty($find)){
            $msg['msgs'] = '流水号找不到';
            $this->ajaxReturn($msg);
        }else{
            if($find['back_money'] != $IncomeMoney){
                $msg['msgs'] = '充值金额输入错误';
                $this->ajaxReturn($msg);
            }
            if($find['seller_id'] > 0 || $find['back_status'] == 2 || $find['back_update'] > 0){
                $msg['msgs'] = '该流水号已被领取';
                $this->ajaxReturn($msg);
            }
        }

        $data['seller_id'] = $SellerID;
        $data['back_status'] = 2;
        $data['back_update'] = time();
        if(M('seller_recharge_back')->where(array('back_tradeno'=>$TradeNo,'back_money'=>$IncomeMoney))->save($data)){
            $FindSeller = M('seller')->where(array('seller_id'=>$SellerID))->find();

            //记录财务信息
            $log['seller_id'] = $FindSeller['seller_id'];
            $log['change_time'] = date('Y-m-d H:i:s',time());
            $log_newmoney = $FindSeller['money'] + $find['back_money'];
            $log['money'] = $find['back_money'];
            $log['money_prev'] = $FindSeller['money'];
            $log['money_next'] = $log_newmoney;
            $log['points'] =  $find['back_money'];
            $log['left_points'] =  $FindSeller['left_points'] + $find['back_money'];
            $log['change_desc'] = "手动充值：".$find['back_money'];
            $log['change_type'] = 1;//类型：1充值；2消费；3-佣金奖励；99其他
            M('seller_finance')->add($log);

            //更新商家金额和积分
            $date['money'] = $FindSeller['money'] + $find['back_money'];
            $date['left_points'] = $FindSeller['left_points'] + $find['back_money'];
            M('seller')->where(array('seller_id'=>$FindSeller['seller_id']))->save($date);

            //商家行为记录；
            $seller_log["seller_id"] =  $FindSeller['seller_id'];
            $seller_log['add_time'] = date("Y-m-d H:i:s",time());
            $seller_log['log_desc'] = "手动充值：".$find['back_money'];
            $seller_log['log_type'] = 6;//日志类型；1-注册日志；2-审核日志；3-登录日志；4-关闭充值订单；5-修改店铺信息；6-充值
            M('seller_log')->add($seller_log);

            $msg['code'] = 1;
            $msg['msgs'] = '充值成功';
        }else{
            $msg['msgs'] = '充值';
        }
        exit(json_encode($msg));
    }

//    public function back(){
//        //if(IS_POST){
//            $data['back_tradeno'] = $_POST['tradeNo'];
//            $data['back_money'] = $_POST['Money'];
//            $data['back_title'] = $_POST['title'];
//            $data['back_memo'] = $_POST['memo'];
//            $data['back_alipay_account'] = $_POST['alipay_account'];
//            $data['back_tenpay_account'] = $_POST['tenpay_account'];
//            $data['back_gateway'] = $_POST['Gateway'];
//            $data['back_sign'] = $_POST['Sign'];
//            $data['back_paytime'] = $_POST['Paytime'];
//
//            $data['back_title'] = strtoupper($data['back_title']);
//
//            $BegToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
//
//            if($data['back_gateway'] == 'alipay'){
//                $type = 1;
//            }else{
//                $type = 2;
//            }
//
//            $where['charge_type'] = 1;
//            $where['charge_descript'] = $data['back_title'];
//            //$where['charge_create'] = array('egt',$BegToday);
//            $where['back_true'] = 1;
//
//            if(!empty($data['back_tradeno'])){
//                $data['back_true'] = 2;
//                $FindRecharge = M('seller_recharge')->where($where)->find();
//                if(!empty($FindRecharge)){
//                    if(M('seller_recharge')->where($where)->save($data)){
//                        $FindSeller = M('Seller')->where(array('seller_id'=>$FindRecharge['seller_id']))->find();
//
//                        //增加用户日志；
//                        $log['seller_id'] = $FindSeller['seller_id'];
//                        $log['add_time'] = date("Y-m-d H:i:s",time());
//                        $log['log_desc'] = '账户充值金额:'.$data['back_money'];
//                        $log['log_type'] = 4;//日志类型；1-注册日志；2-审核日志；3-登录日志；4-充值
//                        M('seller_log')->add($log);
//
//
//                        //增加财务记录
//                        $finamce['seller_id'] = $FindSeller['seller_id'];
//                        $finamce['money'] = $data['back_money'];
//                        $finamce['money_prev'] = $FindSeller['seller_money'];
//                        $finamce['money_next'] =  $FindSeller['seller_money'] + $data['back_money'];
//                        $finamce['change_time'] = date("Y-m-d H:i:s",time());
//                        $finamce['change_desc'] = "充值金额:".$data['back_money'];
//                        $finamce['change_type'] = 1;//类型：1充值；2消费；3-佣金奖励；99其他
//                        $finamce['points'] = $data['back_money'];
//                        $finamce['left_points'] = $FindSeller['left_points'] + $data['back_money'];
//                        M('seller_finance')->add($finamce);
//
//                        //更新用户金额和积分
//                        $date['seller_hismoney'] = $FindSeller['seller_hismoney'] + $data['back_money'];
//                        $date['seller_money'] = $FindSeller['seller_money'] + $data['back_money'];
//                        $date['left_points'] = $FindSeller['left_points'] + $data['back_money'];
//
//                        M('Seller')->where(array('seller_id'=>$FindSeller['seller_id']))->save($date);
//
//                        echo 'Success';exit;
//                    }else{
//                        //echo '支付回调报错，金额：！'.$data['back_money'];exit;
//                        echo 'IncorrectOrder';exit;
//                    }
//                }else{
//                    $insert['back_tradeno'] = $data['back_tradeno'];
//                    $insert['back_money'] = $data['back_money'];
//                    $insert['back_title'] = strtoupper($data['back_title']);
//                    $insert['back_paytime'] = $data['back_paytime'];
//                    $insert['back_create'] = date("Y-m-d H:i:s",time());
//                    M('seller_recharge_back')->add($insert);
//                    //echo '支付回调报错(记录日志)！';exit;
//                    echo 'IncorrectOrder';exit;
//                }
//            }else{
////                echo '支付回调报错！';exit;
//                echo 'IncorrectOrder';exit;
//            }
//        }
//    }
}