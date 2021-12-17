<?php

namespace app\index\controller;


use app\common\model\Article;
use app\common\model\ArticleCategory;
use think\facade\Url;

class IndexController extends Controller
{
    //无需验证登录的方法
    protected $authExcept = [
        'index',
    ];

    //前台模块首页
    public function index()
    {
        //工作动态--轮播
        $work_dt = Article::where(['article_category_id'=>12,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(3)
            ->select();
        //工作动态--研究会动态
        $work_yj = Article::where(['article_category_id'=>12,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //工作动态--会员动态
        $work_hy = Article::where(['article_category_id'=>13,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //学术研究动态
        $gj_xs = Article::where(['article_category_id'=>14,'is_hot'=>1])
            ->append(['new'])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //国际税收研究课题
        $gj_kt = Article::where(['article_category_id'=>15,'is_hot'=>1])
            ->append(['new'])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //国际税收简讯
        $sw1 = Article::where(['article_category_id'=>23,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //涉外税收法规政策
        $sw2 = Article::where(['article_category_id'=>16,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //税收协定
        $sw3 = Article::where(['article_category_id'=>17,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //国别信息
        $sw4 = Article::where(['article_category_id'=>19,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        //走出去指引
        $sw5 = Article::where(['article_category_id'=>20,'is_hot'=>1])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->limit(8)
            ->select();
        $this->assign([
            'work_dt'      => $work_dt,
            'work_yj' => $work_yj,
            'work_hy' => $work_hy,
            'gj_xs' => $gj_xs,
            'gj_kt' => $gj_kt,
            'sw1' => $sw1,
            'sw2' => $sw2,
            'sw3' => $sw3,
            'sw4' => $sw4,
            'sw5' => $sw5,
        ]);
        return $this->fetch();
    }

    //列表
    public function news()
    {
        $parent_id = ArticleCategory::where(['id'=>$this->cata_id])->value('parent_id');
        if(is_null($parent_id)){
            return redirect('/');
        }
        if($parent_id == 0){
            $son = ArticleCategory::where(['parent_id'=>$this->cata_id])->order('id','asc')->find();
            if(!is_null($son)){
                if($this->cata_id == 1) {
                    return redirect('index/active',['cata_id'=>$son['id']]);
                }else{
                    return redirect('index/news',['cata_id'=>$son['id']]);
                }
            }
        }
        $list = Article::where('article_category_id',$this->cata_id)
            ->append(['new'])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->paginate(10);
        $page = $list->render();
        $this->assign([
            'list'      => $list,
            'page' => $page
        ]);
        return $this->fetch();
    }

    //单页面
    public function active()
    {
        if($this->cata_id==11){
            return $this->fetch('contact');//联系我们
        }else{
            $info = Article::where(['article_category_id'=>$this->cata_id])->find();
            if(is_null($info)){
                return redirect('/');
            }
            $this->assign([
                'info'      => $info
            ]);
            return $this->fetch();
        }
    }

    //详情页
    public function details()
    {
        $info = Article::get($this->id);
        $this->assign([
            'info'      => $info,
            'mbx'      => $this->mbx($this->cata_id,0)
        ]);
        return $this->fetch();
    }
    //面包屑
    public function mbx($cata_id,$num)
    {
        $cata_model = ArticleCategory::where(['id'=>$cata_id])->field('id,name,parent_id')->find();

        $jt = $num==0?'':' > ';
        $mbx = "<dd><a href=\"".url('index/news',['cata_id'=>$cata_model['id']])."\">&nbsp;".$cata_model['name'].$jt."</a></dd>";
        if($cata_model['parent_id'] != 0 ){
            $num++;
            $new = $this->mbx($cata_model['parent_id'],$num);
            $mbx = $new.$mbx;
        }
        return $mbx;
    }

    //搜索
    public function search()
    {
        $keyword = $this->param['keyword'];
        $map[] = ['name','like',"%".$keyword."%"];
        $list = Article::where($map)
            ->append(['new'])
            ->order(['is_top'=>'desc','sort_number'=>'asc','id'=>'desc'])
            ->paginate(2,false,['query'=>['keyword'=>$keyword]]);
        $page = $list->render();
        $this->assign([
            'list'      => $list,
            'page' => $page,
            'keyword' => $keyword,
            'count' => count($list)
        ]);
        return $this->fetch();
    }
}
