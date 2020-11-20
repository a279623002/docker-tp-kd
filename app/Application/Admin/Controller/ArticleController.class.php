<?php 
namespace Admin\Controller;
use Think\Controller;
use Admin\Logic\ArticleLogic;
class ArticleController extends BaseController {
	
	//文章列表
	public function show() {
		$ArticleLogic = new ArticleLogic();
		$list = $ArticleLogic->article_list();
		
		$this->assign('list', $list);
		$this->display();
	}
	
	//添加文章
	public function add() {
		$ArticleLogic = new ArticleLogic();
		$article_id = I('get.article_id', '', true);
		if(!empty($article_id)) {
			$detail = $ArticleLogic->get_article_detail($article_id);
			$this->assign('detail', $detail);
		}
		$data = I('post.', '', true);
		if(!empty($data)) {
			$result = $ArticleLogic->add_article($data);
			exit(json_encode($result));
		}
		$this->assign('cate', $cate);
		$this->display();
	}

	//删除文章
	public function del() {
		$ArticleLogic = new ArticleLogic();
		$article_id = I('post.article_id', '', true);
		if(!empty($article_id)) {
			$result = $ArticleLogic->del_article($article_id);
			exit(json_encode($result));
		}
		exit(json_encode(array('state'=>false, 'msg'=>'删除失败')));
	}
}