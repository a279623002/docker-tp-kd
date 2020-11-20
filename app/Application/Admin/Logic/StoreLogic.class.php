<?php
namespace Admin\Logic;
use Think\Model\RelationModel;
class StoreLogic extends RelationModel {

    //商店列表
    public function get_store_list($where) {
        $Model = M('store');
        if(!empty($where['mobile'])) {
            $map['mobile'] = array('like', '%'.$where['mobile'].'%');
        }
        $map = array_filter($map);
        $count = $Model->where($map)->count();
        $p = getpage($count,15);
        $store_list = $Model->where($map)->limit($p->firstRow, $p->listRows)->order('addtime desc')->select();
        foreach ($store_list as $key => $value) {
            $store_list[$key]['addtime'] = date('Y-m-d H:i:s', $store_list[$key]['addtime']);
        }
    
        return array('list'=>$store_list, 'page'=>$p);
    }

    //商店密码修改
    public function changePw($data) {
        $Model = M('store');
        $data['loginpass'] = encrypt($data['new_password']);
        if ($Model->save($data)){
            $result = array('state'=>true,'msg'=>'修改密码成功! 该商店新密码为：'.$data['new_password']);
        }else{
            $result = array('state'=>false,'msg'=>'修改密码失败!');
        }
        return $result;
    }

    //商店冻结
    public function freeze($store_id, $status) {
        $Model = M('store');
        if ($Model->where(array('store_id'=>$store_id))->save(array('status'=>$status))){
            $result = array('state'=>true,'msg'=>'操作成功!');
        }else{
            $result = array('state'=>false,'msg'=>'操作失败!');
        }
        return $result;
    }

    //商家发货地址
    public function get_store_address($key) {
        $Model = M('store_address');
        if(!empty($key)) {
            $where['wangwang']  = array('like', '%'.$key.'%');
            $where['manager']  = array('like','%'.$key.'%');
            $where['_logic'] = 'or';
        }
        $where = array_filter($where);
        $count = $Model->where($where)->count();
        $p = getpage($count,15);
        $list = $Model->where($where)->limit($p->firstRow, $p->listRows)->order('addtime desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
            $list[$key]['store'] = M('store')->where(array('store_id'=>$list[$key]['store_id']))->field('sellername, mobile')->find();
        }
        return array('list'=>$list, 'page'=>$p);
    }

    //删除商家发货地址
    public function del_address($id) {
        if(M('store_address')->where(array('id'=>$id))->delete()) {
            $result = array('state'=>true, 'msg'=>'删除成功!');
        } else {
            $result = array('state'=>false, 'msg'=>'删除失败!');
        }
        return $result;
    }

    //商家发货地址审核
    public function auditing($id, $status) {
        $Model = M('store_address');
        if ($Model->where(array('id'=>$id))->save(array('status'=>$status))){
            $result = array('state'=>true,'msg'=>'操作成功!');
        }else{
            $result = array('state'=>false,'msg'=>'操作失败!');
        }
        return $result;
    }
    

}