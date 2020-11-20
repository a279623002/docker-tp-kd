<?php
namespace Home\Controller;
use Think\Controller;
class ExpressController extends BaseController {
	
	//申请底单说明 
	public function didan() {
		$this->display();
	}
	
	//批量导入订单和批量导出单号说明
	public function shuoming(){
		$this->display();
	}

	//任务纪录
	public function explist(){
		$where = I('get.express') == '' ? '' : I('get.express');
		$data = I('post.');
		if(!empty($data)) {
			$key = I('post.key', '', true);
			$where1['kd_task.order_no'] = array('like', '%'.$key.'%');
			$where2['kd_task.receiver_name'] = array('like', '%'.$key.'%');

            $where['_complex'] = array(
                $where1,
                $where2,
                '_logic' => 'or'
            );
		}
		$where = array_filter($where);
		$where['kd_task.seller_id'] = $this->seller_id;
		$count = M('task')->where($where)->count();
        $p = getpage($count,15);
		$list = M('task')->where($where)
            ->join('kd_task_kongbao as b on b.task_id = kd_task.task_id','LEFT')
            ->join('kd_seller_store as c on c.store_id = kd_task.store_id','LEFT')
            ->join('kd_kuaidi as d on d.id = b.express_id','LEFT')
            ->field("kd_task.task_id,c.store_nick,kd_task.order_no,kd_task.add_time,kd_task.status,kd_task.remark_state,b.tb_status,b.tbtime,d.k_name as express_name,b.waybill_no,kd_task.goods_weight,kd_task.receiver_name,kd_task.receiver_mobile,kd_task.receiver_provice,kd_task.receiver_city,kd_task.receiver_district,kd_task.receiver_address")
            ->order('kd_task.task_id desc')->select();
//		foreach ($list as $key => $value) {
//			$list[$key]['store_nick'] = M('seller_store')->where(array('id'=>$list[$key]['address_id']))->getField('store_nick');
//		}
		$this->assign('page', amazeui_page_style($p->show()));
		$this->assign('list', $list);
		$this->display();
	}
	
	//发布任务
	public function add(){
		
		$this->display();
	}
	
	//发布圆通任务
	public function newadd(){
        $express_id = I('post.express', '', true);
        if($express_id=='' || empty($express_id))
        {
            $express_id = 1;
        }
		// $address = M('seller_address')->where(array('seller_id'=>$this->seller_id))->order(array('status desc'))->getField('id,province,city,district,address,wangwang');
		$level_id = M('seller')->where(array('seller_id'=>$this->seller_id))->getField('level_id');
		//$level = M('seller_level')->where(array('level_id'=>$level_id))->field('level_name,reduction')->find();
        $level = M('kuaidi_level')->where(array('kd_kuaidi_level.level_id'=>$level_id,'kd_kuaidi_level.express_id'=>$express_id))
            ->join("kd_seller_level as b on b.level_id = kd_kuaidi_level.level_id ","LEFT")
            ->field('level_name,price')->find();
		$store = M('seller_store')->where(array('seller_id'=>$this->seller_id, 'status'=>2))->order(array('status desc'))->getField('store_id,province,city,district,address,store_nick,goods_weight');

		$this->assign('store', $store);
		// $this->assign('address', $address);
		$this->assign('level', $level);
		$this->display();
	}

	//发布圆通任务添加
	public function expressAdd() {
		$data = I('post.', '', true);
		if(empty($data['express'])) exit(json_encode(array('state'=>false,'msg'=>'请选择快递')));
		if(empty($data['store_id'])) exit(json_encode(array('state'=>false,'msg'=>'请选择店铺')));
		if(empty($data['address_key'])) exit(json_encode(array('state'=>false,'msg'=>'请选择发货地址')));
		if(empty($data['order_no'])) exit(json_encode(array('state'=>false,'msg'=>'请填写单号')));

		// if(empty($data['min_weight'])) exit(json_encode(array('state'=>false,'msg'=>'请输入最低重')));
		// if(empty($data['max_weight'])) exit(json_encode(array('state'=>false,'msg'=>'请输入最高重')));
		// if(empty($data['goods'])) exit(json_encode(array('state'=>false,'msg'=>'请输入商品名称')));
		// if(empty($data['aim_address'])) exit(json_encode(array('state'=>false,'msg'=>'请输入收货地址')));
		// $data['number'] = md5(uniqid(mt_rand(), true));
        $bool = true;
        $str_msg = "";
        //店铺VIP检查；
        //店铺打印助手检查；
        $store = M('seller_store')->where(array('store_nick'=>$data['store_id']))->find();
        $vip_deadline = strtotime($store['vip_deadline']);
        $print_deadline = strtotime($store['print_deadline']);
        if($vip_deadline>=time())
        {}
        else
        {
            $bool = false;
            $str_msg = "您的选择的店铺：".$data['store_id']."的VIP到期了！";

        }
        if($print_deadline>=time())
        {}
        else
        {
            $bool = false;
            $str_msg = "您的选择的店铺：".$data['store_id']."的打印助手需要续费了哦！";

        }
        //余额不足检查；
        $seller = M('seller')->where(array('seller_id'=>$this->seller_id))->find();
        if($seller['money']>0)
        {}
        else
        {
            $bool = false;
            $str_msg = "您的账户余额不足，请先充值！";
        }
        //订单号不能重复
        $task = M('task')->where(array('order_no'=>$data['order_no']))->find();
        if(!empty($task))
        {
            $bool = false;
            $str_msg = "订单编号：".$data['order_no']."已经存在！";
        }
        else
        {

        }

        if($bool) {
            $adderess_key = M('seller_store')->where(array('store_id' => $data['address_key']))->find();
            $data['send_name'] = $adderess_key['name'];
            $data['send_mobile'] = $adderess_key['mobile'];
            $data['send_provice'] = $adderess_key['province'];
            $data['send_city'] = $adderess_key['city'];
            $data['send_district'] = $adderess_key['district'];
            $data['send_address'] = $adderess_key['address'];
            $data['seller_id'] = $this->seller_id;
            $data['add_time'] = date('Y-m-d H:i:s',time());
            $data['store_id'] = $data['address_key'];
            if (M('task')->add($data)) {
                exit(json_encode(array('state' => true, 'msg' => '提交成功')));
            } else {
                exit(json_encode(array('state' => false, 'msg' => '提交失败')));
            }
        }
        else
        {
            exit(json_encode(array('state' => false, 'msg' =>$str_msg)));
        }
	}
	
	//批量发布任务
	public function uploads() {
		$data = I('post.', '', true);
		if(!empty($data)) {
			if(empty($data['express'])) exit(json_encode(array('state'=>false,'msg'=>'请选择快递')));
			if(empty($data['store_id'])) exit(json_encode(array('state'=>false,'msg'=>'请选择店铺')));
			if(empty($data['address_key'])) exit(json_encode(array('state'=>false,'msg'=>'请选择发货地址')));
			$adderess_key= M('seller_store')->where(array('store_id'=>$data['address_key']))->find();
			$data['send_name'] = $adderess_key['name'];
			$data['send_mobile'] = $adderess_key['mobile'];
			$data['send_province'] = $adderess_key['province'];
			$data['send_city'] = $adderess_key['city'];
			$data['send_district'] = $adderess_key['district'];
			$data['send_address'] = $adderess_key['address'];
			$data['seller_id'] = $this->seller_id;
			$data['add_time'] = date('Y-m-d H:i:s');
			$excel = empty($_FILES['excel']) ? '' : $_FILES['excel'];
			$store = M('seller_store')->where(array('seller_id'=>$this->seller_id))->find();
			if (!empty($excel) && $excel['error'] == 0){
				// if($excel['type'] !='application/octet-stream'){
				// 	$this->error('不支持的文件，请重新上传');
				// }
				$file_name = upload_excel($excel);
				$exlDate = import_plate_exl($file_name);
				$arr['count'] = count($exlDate);
				$arr['seller_id'] = $this->seller_id;
				$arr['express'] = $data['express'];
				$arr['shop'] = $adderess_key['store_nick'];
				$arr['order_time'] = time();
				$data['uplog_id'] = M('uplog')->add($arr);
				if($data['uplog_id']){
					// $result = $SubjectLogic->subjectImport($exlDate, $cate_id);
					
					foreach ($exlDate as $key=>$value){
						$data['order_no'] = $exlDate[$key]['order_no'];
						if(empty($data['order_no'])) exit(json_encode(array('state'=>false,'msg'=>'请填写单号')));
						// $data['number'] = md5(uniqid(mt_rand(), true));
						if (empty($exlDate[$key]['goods_weight'])){
							$data['goods_weight'] = $store['goods_weight'];
						}else{
							$data['goods_weight'] = $exlDate[$key]['goods_weight'];
						}
						if(M('task')->add($data)) {
							
						}else {
							exit(json_encode(array('state'=>false,'msg'=>'导入失败')));
						}
					}
					exit(json_encode(array('state'=>true,'msg'=>'导入成功')));
				}
	
			}
		}
	}
	
	//导入记录
	public function uplog() {
		$key = I('post.key', '', true);
		$where['shop'] = array('like', '%'.$key.'%');
		$where['seller_id'] = $this->seller_id;
		$where = array_filter($where);
		$count = M('uplog')->where($where)->count();
        $p = getpage($count,15);
		$list = M('uplog')->where($where)->select();
		foreach ($list as $key => $value) {
			$list[$key]['time'] = date('Y-m-d H:i:s', $list[$key]['order_time']);
			$list[$key]['success_count'] = M('task')->where(array('uplog_id'=>$list[$key]['uplog_id'], 'status'=>2))->count();
		}
		$this->assign('page', amazeui_page_style($p->show()));
		$this->assign('list', $list);
		$this->display();
	}

	//删除导入记录
	public function uplogDel() {
		$uplog_id = I('post.uplog_id', '', true);
		if(!empty($uplog_id)) {
			if(M('uplog')->where(array('uplog_id'=>$uplog_id))->delete()) {
				exit(json_encode(array('state'=>true, 'msg'=>'删除成功')));
			} else {
				exit(json_encode(array('state'=>false, 'msg'=>'删除失败')));
			}
		} else {
			exit(json_encode(array('state'=>false, 'msg'=>'未指定记录')));
		}
	}
}