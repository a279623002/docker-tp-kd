<?php 
namespace Admin\Logic;
use Think\Model\RelationModel;
class CategoryLogic extends RelationModel{
	
	//获取文章分类列表
	public function get_category() {
		$result = M('category')->order('sort desc')->select();
		return $result;
	}

	//删除文章分类
	public function del_category($cate_id) {
		if (M('category')->where(array('cate_id'=>$cate_id))->delete()) {
			return array('state'=>true, 'msg'=>'删除成功');
		}
		return array('state'=>false, 'msg'=>'删除失败');
	}

	//添加文章分类
	public function add_category($data) {
		if (empty($data['name'])) return array('state'=>false, 'msg'=>'缺少栏目名称');
		if (empty($data['template'])) return array('state'=>false, 'msg'=>'缺少模板');
		if (empty($data['content'])) return array('state'=>false, 'msg'=>'缺少内容');
		if (empty($data['ips'])) return array('state'=>false, 'msg'=>'缺少限制IP');
		if (empty($data['sort'])) return array('state'=>false, 'msg'=>'缺少排序');
		$data['addtime'] = time();
		if (!empty($data['cate_id'])) {
			if (M('category')->save($data)) {
				return array('state'=>true, 'msg'=>'添加成功');
			}
		} else {
			if (M('category')->add($data)) {
				return array('state'=>true, 'msg'=>'添加成功');
			}
		}
		return array('state'=>false, 'msg'=>'添加失败');

	}

	//获取文章分类详情
	public function detail_category($cate_id) {
		$result = M('category')->where(array('cate_id'=>$cate_id))->find();
		return $result;
	}

}
?>