<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class StoreController extends BaseController {
	
	//店铺管理
	public function store() {
		$Model = M('seller_store');
		$seller_id = session('seller_id');
		$count = $Model->where(array('seller_id'=>$seller_id))->count();
		$list = $Model->where(array('seller_id'=>$seller_id))->select();
		$this->assign('count', $count);
		$this->assign('list', $list);
		$this->display();
	}

	//添加店铺
	public function addStore() {
		$Model = M('seller_store');
		$data = I('post.', '', true);
		if (!empty($data)) {
			if(empty($data['seller_id'])) exit(json_encode(array('state'=>false, 'msg'=>'请先登录!')));
			if(empty($data['store_nick'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入店铺名!')));
			if(empty($data['store_type'])) exit(json_encode(array('state'=>false, 'msg'=>'请选择平台!')));
			if(empty($data['name'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入发货人姓名!')));
			if(empty($data['mobile'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入发货人电话!')));
			if(empty($data['manager'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入掌柜号!')));
			if(empty($data['goods_weight'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入掌柜号!')));
			if(empty($data['address'])) exit(json_encode(array('state'=>false, 'msg'=>'请输入详细地址!')));
			$image = empty($_FILES['image']) ? '' : $_FILES['image'];
			if ($image['error'] == 0){
				$img1 = upload_image($image);
				$data['image'] = $img1['image']['savename'];
			}

            $store_list =  $Model->where(array('store_nick'=>$data['store_nick']))->select();
            if(!empty($store_list))
            {
                exit(json_encode(array('state'=>false, 'msg'=>'该店铺('.$data['store_nick'].')已经存在！!')));
            }
            else {

                $data['add_time'] = date('Y-m-d H:i:s');
                if (!empty($data['store_id'])) {
                    if ($Model->save($data)) {
                        $result = array('state' => true, 'msg' => '修改成功!');
                    } else {
                        $result = array('state' => false, 'msg' => '修改失败!');
                    }
                    exit(json_encode($result));
                } else {
                    if ($Model->add($data)) {
                        $result = array('state' => true, 'msg' => '添加成功!');
                    } else {
                        $result = array('state' => false, 'msg' => '添加失败!');
                    }
                    exit(json_encode($result));
                }
            }
		}
		
	}

    //编辑店铺信息
    public  function  editStore()
    {
        $Model = M('seller_store');
        $data = I('post.', '', true);
        if (!empty($data)) {
            if (empty($data['store_id'])) exit(json_encode(array('state' => false, 'msg' => '店铺数据异常!!')));
            if (empty($data['seller_id'])) exit(json_encode(array('state' => false, 'msg' => '请先登录!')));
            if (empty($data['name'])) exit(json_encode(array('state' => false, 'msg' => '请输入发货人姓名!')));
            if (empty($data['mobile'])) exit(json_encode(array('state' => false, 'msg' => '请输入发货人电话!')));
            if (empty($data['address'])) exit(json_encode(array('state' => false, 'msg' => '请输入详细地址!')));
            if (empty($data['goods_weight']) || $data['goods_weight']<=0) exit(json_encode(array('state' => false, 'msg' => '请设置宝贝默认重量!!')));
            $data['update_time'] = date('Y-m-d H:i:s');

            if ($Model->save($data)) {
                $result = array('state' => true, 'msg' => '修改成功!');

                $log['seller_id'] = $data['seller_id'];
                $log['add_time'] = date("Y-m-d H:i:s",time());
                $log['log_desc'] = '修改店铺信息';
                $log['log_type'] = 5;//日志类型；1-注册日志；2-审核日志；3-登录日志；4-关闭充值订单；5-修改店铺信息
                M('seller_log')->add($log);

            } else {
                $result = array('state' => false, 'msg' => '修改失败!');
            }
            exit(json_encode($result));
        }
    }
    
    //取店铺名称
    public function get_store_name(){
    	$store_id = I('get.store_id', '', true);
    	$store_name = M('seller_store')->where(array('store_id'=>$store_id))->getField('store_nick');
    	exit(json_encode($store_name));
    }

    //获取单个店铺信息
    public function getStore($store_id) {
        $result = M('seller_store')->where(array('store_id'=>$store_id))->find();
        exit(json_encode($result));
    }

	//删除店铺
	public function delStore($store_id) {
		if (M('seller_store')->where(array('store_id'=>$store_id))->delete()) {
			$result = array('state'=>true, 'msg'=>'删除成功!');
		}else {
			$result = array('state'=>false, 'msg'=>'请选择发货地址!');
		}
		exit(json_encode($result));
	}

	//智能助手
	public function assistant() {
		$Model = M('seller_store');

		//店铺信息
		$data = I('post.', '', true);
		if(!empty($data)) {
			if($Model->save($data)) {
				$result = array('state'=>true, 'msg'=>'操作成功!');
			}else {
				$result = array('state'=>false, 'msg'=>'操作失败!');
			}
			exit(json_encode($result));
		}	
		$seller_id = session('seller_id');
		$count = $Model->where(array('seller_id'=>$seller_id))->count();
		$list = $Model->where(array('seller_id'=>$seller_id))->select();
		$renew = M('user_renew')->order('month asc')->select();
//		foreach ($list as $key => $value) {
//			$list[$key]['print_deadline'] = date('Y-m-d H:i:s', $list[$key]['print_deadline']);
//		}
		$this->assign('renew', $renew);
		$this->assign('count', $count);
		$this->assign('list', $list);

		$this->display();
	}

	//智能助手 续费
	public function renew() {
		$data = I('post.', '', true);
		if(empty($data['store_id'])) exit(json_encode(array('state'=>false, 'msg'=>'未指定店铺!')));
		if(empty($data['renew_id'])) exit(json_encode(array('state'=>false, 'msg'=>'未选择续费月份!')));
		$renew = M('user_renew')->where(array('renew_id'=>$data['renew_id']))->find();

		//余额扣费
		$seller_id = session('seller_id');
		$seller = M('seller')->where(array('seller_id'=>$seller_id))->find();
		$result_money = $seller['money'] - $renew['price'];
		if($result_money < 0) {
			exit(json_encode(array('state'=>false, 'msg'=>'余额不足!')));
		}
		if($renew['price'] != 0) {
			$result = M('seller')->where(array('seller_id'=>$seller_id))->save(array('money'=>$result_money));
		} else {
			$result = true;
		}
		// 添加到期时长
		$print_deadline = M('seller_store')->where(array('store_id'=>$data['store_id']))->getField('vip_deadline');
		if (strtotime($print_deadline) < time()) {
			$now = time();  
		} else {
			$now = strtotime($print_deadline);
		}
		$newtime = strtotime("+".$renew['month']."months", $now);
        $newtime = date("Y-m-d",$newtime);
		$result1 = M('seller_store')->where(array('store_id'=>$data['store_id']))->save(array('vip_deadline'=>$newtime));

		// 账户记录
		$data = array(
			'seller_id'		=>	$seller_id,
			'money'			=>	$renew['price'],
			'money_next'			=>	$result_money,
			'money_prev'			=>	$seller['money'],
			'points'			=>	0,
			'change_time'	=>	date("Y-m-d H:i:s",time()),
			'change_desc'	=>	'智能助手 续费'.$renew['month'].'个月',
			'change_type'	=>	2
		);
		$result2 = M('seller_finance')->add($data);

		if ($result && $result1 && $result2) {
			//上级是商家推广模式
			if (!empty($seller['parent_id'])){
				
				$topSeller = M('seller')->where(array('seller_id'=>$seller['parent_id']))->find();
				
				if ($topSeller['spread'] ==3){
					$spread_log = M('seller_spread_log')->where(array('seller_id'=>$seller_id,'top_seller_id'=>$topSeller['seller_id']))->select();
					if (empty($spread_log)){
							M('seller_spread_log')->add(array('seller_id'=>$seller_id,'top_seller_id'=>$topSeller['seller_id'],'addtime'=>time()));
							$res = array(
									'seller_id'		=>	$topSeller['seller_id'],
									'money'			=>	200,
									'money_next'			=>	$topSeller['money'] + 200,
									'money_prev'			=>	$topSeller['money'],
									'points'			=>	0,
									'change_time'	=>	date("Y-m-d H:i:s",time()),
									'change_desc'	=>	'推广奖励',
									'change_type'	=>	3
							);
							if (M('seller_finance')->add($res)){
								M('seller')->where(array('seller_id'=>$topSeller['seller_id']))->setInc('money', 200);
							}
					}
				}
			}
			
			exit(json_encode(array('state'=>true, 'msg'=>'续费成功!')));
		} else {
			exit(json_encode(array('state'=>false, 'msg'=>'续费失败!')));
		}
	}


    public function checkid(){
        $SellerID = $this->seller_id;
        if($SellerID<=0){
            $msg['code'] = 2;
            $msg['msgs'] = '连接超时，请重新登录';
            $this->ajaxReturn($msg);exit;
        }
    }


    public function decodeUnicode($str){
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str);
    }

    public function http_post_json($url,$jsonStr){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array($httpCode, $response);
    }

    //获取接口时长
    public  function  assistant_ali()
    {
        if(IS_POST){
            $msg['code'] = 0;
            $this->checkid();
            $SellerID = $this->seller_id;
            $store_id = I('post.storeid','','intval');

            $store_where['store_id'] = $store_id;
            $store_where['seller_id'] = $SellerID;
            $store = M('seller_store')->where($store_where)->find();
            if(empty($store)){
                $msg['msgs'] = '店铺异常';
                $this->ajaxReturn($msg);
            }
            $post['nick'] = $store['manager'];
            $arr[0] = $post;
            $arr = json_encode($arr);
            $arr = $this->decodeUnicode($arr);
            //dump(json_encode($arr));
            $res = $this->http_post_json("http://sh.marymeng.com/kuaidi/get-isvorder",$arr);

            if($res[0]!=200){
                $msg['msgs'] = '请求超时，请重试';
                $this->ajaxReturn($msg);
            }
            $res = json_decode($res[1],true);
            if(empty($res)){
                $msg['msgs'] = '请先完成前面步骤，再点击授权';
                $this->ajaxReturn($msg);
            }
            //$time = strtotime($res[0]['deadline']);
            $time = strtotime($res['data']['deadline']);//取data内容；
            if($time<time()){
                $msg['msgs'] = 'ERP打印 已过期，请重新订购';
                $this->ajaxReturn($msg);
            }
            $store_res['print_deadline'] = date("Y-m-d H:i:s",$time);
            $store_res['assistant'] = 2;//1--未授权，2-已授权
            M('seller_store')->where($store_where)->save($store_res);
            $msg['code'] = 1;
            $msg['msgs'] = '授权成功';
            exit(json_encode($msg));
        }
    }
	
}