<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected $seller_id = 0;
    public $seller = array();
    
    public function __construct() {
        parent::__construct();
        if (session('?seller_id')){
            $seller_id = session('seller_id');
            $seller = M('seller')->where(array('seller_id'=>$seller_id))->find();
            session('seller',$seller);
            $this->change_seller_grade($seller);
            $this->seller = $seller;
            $this->seller_id = $seller['seller_id'];
            $this->assign('seller',$seller);
            $this->assign('seller_id',$this->seller_id);

            //顶部公告列表；
            $article =  M('article')
                ->order(array('add_time desc'))
                ->select();

            //顶部公共信息
            $data['task'] = M('task')->where(array('seller_id'=>$seller_id))->count();
            $data['son'] = M('seller')->where(array('parent_id'=>$seller_id))->count();
            $grade = M('seller_level')
                ->where(array('is_show'=>1))
                ->join("kd_kuaidi_level as b on b.level_id = kd_seller_level.level_id and b.express_id=1 ","LEFT")//默认把圆通的价格显示出来
                ->select();
    
            //获取用户等级、积分
            $seller_level= M('seller')->where(array('seller_id'=>$seller_id))->find();
            $seller_level['level_name'] = M('seller_level')->where(array('level_id'=>$seller_level['level_id']))->getField('level_name');
            $grade_count = M('seller_level')->count();
            if($seller_level['level_id'] < $grade_count) {
                $up_grade_point = M('seller_level')->where(array('level_id'=>($seller_level['level_id'] + 1)))->getField('growth_from');
                $seller_level['up_point']= $up_grade_point - $seller_level['left_points'];
                $seller_level['up_level_name'] = M('seller_level')->where(array('level_id'=>($seller_level['level_id'] + 1)))->getField('level_name');
            }
            
            $this->assign('top_seller_level', $seller_level);
            $this->assign('top_grade', $grade);
            $this->assign('top_article', $article);
            $this->assign('top_data', $data);
        }else{
            $nologin = array(
                'login','register','logout','verify'
            );
            if(!in_array(ACTION_NAME,$nologin)){
                header("location:".U('Home/Seller/login'));
                exit;
            }
        }
		$flink = M('flink')->order('sort desc')->select();
        $notice = M('system')->getField('index_notice');
		$this->assign('flink', $flink);
        $this->assign('notice', $notice);
        $this->assign('seller', $seller);
    }

    //判断用户等级
    public function change_seller_grade($seller) {
        $grade_point = M('seller_level')->field('growth_from,level_id')->select();
        foreach ($grade_point as $key => $value) {
            if ($seller['left_points'] >= $grade_point[$key]['growth_from']) {
                M('seller')->where(array('seller_id'=>$seller['seller_id']))->save(array('level_id'=>$grade_point[$key]['level_id']));
            }
        }
    }
}