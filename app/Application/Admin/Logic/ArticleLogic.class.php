<?php 
namespace Admin\Logic;
use Think\Model\RelationModel;
class ArticleLogic extends RelationModel{
	

	//编辑、添加文章
	public function add_article($data) {
		if(empty($data['title'])) return array('state'=>false, 'msg'=>'缺少标题');
		if(empty($data['content'])) return array('state'=>false, 'msg'=>'缺少内容');
		if(empty($data['cate'])) return array('state'=>false, 'msg'=>'缺少分类');
		$data['add_time'] = date('Y-m-d H:i:s',time());
		if(!empty($data['article_id'])) {
			if(M('article')->save($data)) {
				return array('state'=>true, 'msg'=>'添加成功');
			} 
		}else {
			if(M('article')->add($data)) {
				return array('state'=>true, 'msg'=>'添加成功');
			} 
		}
		return array('state'=>false, 'msg'=>'添加失败');
	}

	//文章列表
	public function article_list() {
		$result = M('article')->order('cate desc')->field('article_id,title,cate,add_time')->select();
		foreach ($result as $key => $value) {
			//$result[$key]['addtime'] = date('Y-m-d H:i:s', $result[$key]['add_time']);
			switch ($result[$key]['cate']) {
				case 1:
					$result[$key]['cate_name'] = '运营技巧';
					break;

				case 2:
					$result[$key]['cate_name'] = '推广赚钱';
					break;
				
				default:
					$result[$key]['cate_name'] = '加盟代理';
					break;
			}
		}
		return $result;
	}

	//删除文章
	public function del_article($article_id) {
		if(M('article')->where(array('article_id'=>$article_id))->delete()) {
			$result = array('state'=>true, 'msg'=>'删除成功');
		}else {
			$result = array('state'=>false, 'msg'=>'删除失败');
		}
		return $result;
	}

	//获取文章详情
	public function get_article_detail($article_id) {
		$detail = M('article')->where(array('article_id'=>$article_id))->find();
		
		switch ($detail['cate']) {
			case 1:
				$detail['cate_name'] = '运营技巧';
				break;

			case 2:
				$detail['cate_name'] = '推广赚钱';
				break;
			
			default:
				$detail['cate_name'] = '加盟代理';
				break;
		}
		return $detail;
	}

}
?>