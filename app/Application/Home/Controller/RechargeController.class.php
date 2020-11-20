<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 19:32
 */

namespace Home\Controller;
use Think\Controller;

class RechargeController extends Controller {


    public function back(){
        //if(IS_POST){
        $data['back_tradeno'] = $_POST['tradeNo'];
        $data['back_money'] = $_POST['Money'];
        $data['back_title'] = $_POST['title'];
        $data['back_memo'] = $_POST['memo'];
        $data['back_alipay_account'] = $_POST['alipay_account'];
        $data['back_tenpay_account'] = $_POST['tenpay_account'];
        $data['back_gateway'] = $_POST['Gateway'];
        $data['back_sign'] = $_POST['Sign'];
        $data['back_paytime'] = $_POST['Paytime'];

        $data['back_title'] = strtoupper($data['back_title']);

        $BegToday = mktime(0,0,0,date('m'),date('d'),date('Y'));

        if($data['back_gateway'] == 'alipay'){
            $type = 1;
        }else{
            $type = 2;
        }

        $where['charge_type'] = 1;
        $where['charge_descript'] = $data['back_title'];
        //$where['charge_create'] = array('egt',$BegToday);
        $where['back_true'] = 1;

        if(!empty($data['back_tradeno'])){
            $data['back_true'] = 2;
            $FindRecharge = M('seller_recharge')->where($where)->find();
            if(!empty($FindRecharge)){
                if(M('seller_recharge')->where($where)->save($data)){
                    $FindSeller = M('Seller')->where(array('seller_id'=>$FindRecharge['seller_id']))->find();
                    //增加用户日志；
                    $log['seller_id'] = $FindSeller['seller_id'];
                    $log['add_time'] = date("Y-m-d H:i:s",time());
                    $log['log_desc'] = '账户充值金额:'.$data['back_money'];
                    $log['log_type'] = 4;//日志类型；1-注册日志；2-审核日志；3-登录日志；4-充值
                    M('seller_log')->add($log);

                    //增加财务记录
                    $finamce['seller_id'] = $FindSeller['seller_id'];
                    $finamce['money'] = $data['back_money'];
                    $finamce['money_prev'] = $FindSeller['money'];
                    $finamce['money_next'] =  $FindSeller['money'] + $data['back_money'];
                    $finamce['change_time'] = date("Y-m-d H:i:s",time());
                    $finamce['change_desc'] = "充值金额:".$data['back_money'];
                    $finamce['change_type'] = 1;//类型：1充值；2消费；3-佣金奖励；99其他
                    $finamce['points'] = $data['back_money'];
                    $finamce['left_points'] = $FindSeller['left_points'] + $data['back_money'];
                    M('seller_finance')->add($finamce);
                    //更新用户金额和积分
                   // $date['seller_hismoney'] = $FindSeller['seller_hismoney'] + $data['back_money'];
                    $date['money'] = $FindSeller['money'] + $data['back_money'];
                    $date['left_points'] = $FindSeller['left_points'] + $data['back_money'];

                    M('Seller')->where(array('seller_id'=>$FindSeller['seller_id']))->save($date);
                    echo 'Success';exit;
                }else{
                    //echo '支付回调报错，金额：！'.$data['back_money'];exit;
                    echo 'IncorrectOrder';exit;
                }
            }else{
                $insert['back_tradeno'] = $data['back_tradeno'];
                $insert['back_money'] = $data['back_money'];
                $insert['back_title'] = strtoupper($data['back_title']);
                $insert['back_paytime'] = $data['back_paytime'];
                $insert['back_create'] = date("Y-m-d H:i:s",time());
                M('seller_recharge_back')->add($insert);
                //echo '支付回调报错(记录日志)！';exit;
                echo 'IncorrectOrder';exit;
            }
        }else{
//                echo '支付回调报错！';exit;
            echo 'IncorrectOrder';exit;
        }
    }
//    }


    //删除店铺
    public function close($charge_id) {
        $charge = M('seller_recharge')->where(array('charge_id'=>$charge_id))->find();
        if (M('seller_recharge')->save(array('charge_id'=>$charge_id,"back_true"=>3))) {

            $log['seller_id'] = $charge['seller_id'];
            $log['add_time'] = date("Y-m-d H:i:s",time());
            $log['log_desc'] = '关闭充值订单';
            $log['log_type'] = 4;//日志类型；1-注册日志；2-审核日志；3-登录日志；4-关闭充值订单
            M('seller_log')->add($log);

            $result = array('state'=>true, 'msg'=>'关闭成功!');
        }else {
            $result = array('state'=>false, 'msg'=>'关闭失败!');
        }
        exit(json_encode($result));
    }


}