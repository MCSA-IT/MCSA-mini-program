<?php
/**
 * 前台基础控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\m\controller;

use app\common\model\Article;
use app\common\model\ArticleCategory;
use app\common\model\Link;
use app\common\model\Setting;
use app\common\model\User;

class Controller extends \think\Controller
{

    protected $param;
    /**
     * 当前url
     * @var string
     */
    protected $url;

    /**
     * 当前用户ID
     * @var int
     */
    protected $uid = 0;

    /**
     * 当前用户
     * @var User
     */
    protected $user;

    /**
     * 无需验证权限的url
     * @var array
     */
    protected $authExcept = [];

    /**
     * 当前用户
     * @var User
     */
    protected $webnav;

    /**
     * 当前id
     */
    protected $id;

    /**
     * 当前栏目id
     */
    protected $cata_id;

    /**
     * 网站信息
     */
    protected $webinfo;

    protected function initialize()
    {
        $request = $this->request;
        $this->param = $request->param();
        $this->id = isset($this->param['id'])?$this->param['id']:0;
        $this->cata_id = isset($this->param['cata_id'])?$this->param['cata_id']:0;
        $this->webnav = $this->nav();
        $this->webinfo = $this->webinfo();
        $this->assign([
            'webnav'      => $this->webnav,
            'cata_id'  =>$this->cata_id,
            'webinfo'  =>$this->webinfo,
            'tdk' => $this->tdk($this->cata_id),
            'navpid' => $this->navpid($this->cata_id),
            'link' => Link::all()
        ]);
    }

    //导航高亮父级id
    public function navpid($id)
    {
        $data = [];
        $pid_one = $pid_two = $top_title = '';
        if($id != 0) {
            $parent_id = ArticleCategory::where(['id' => $id])->value("parent_id");
            if (!is_null($parent_id)&&$parent_id != 0) {
                $parent_id_new = ArticleCategory::where(['id' => $parent_id])->value("parent_id");
                if ($parent_id_new == 0) {
                    $pid_one = $parent_id;
                } else {
                    $pid_one = $parent_id_new;
                    $pid_two = $parent_id;
                }
            }
        }
        if($pid_one != ''){
            $top_title = ArticleCategory::where(['id' => $pid_one])->value("name");
        }else{
            $top_title = ArticleCategory::where(['id' => $id])->value("name");
        }
        $now_title = ArticleCategory::where(['id' => $id])->value("name");

        $data['pid_one'] = $pid_one;
        $data['pid_two'] = $pid_two;
        $data['top_title'] = $top_title;
        $data['now_title'] = $now_title;
        return $data;
    }
    //网站信息
    public function tdk($id)
    {
        $webinfo = $this->webinfo;
        $data = [];
        $data['title'] = '';
        $data['des'] = '';
        $data['key'] = '';
        if($id == 0){
            $data['title'] = $webinfo['title'];
            $data['des'] = $webinfo['des'];
            $data['key'] = $webinfo['key'];
        }else{
            $cate = ArticleCategory::get($id);
            if(!is_null($cate)){
                $data['title'] = $cate['name']."-".$webinfo['title'];
                $data['des'] = $cate['des'];
                $data['key'] = $cate['key'];
            }else{
                $article = Article::get($id);
                if(!is_null($article)){
                    $data['title'] = $article['name']."-".$webinfo['title'];
                    $data['des'] = $article['des'];
                    $data['key'] = $article['key'];
                }
            }
        }
        return $data;
    }
    //网站信息
    public function webinfo()
    {
        $arr = [];
        $data = Setting::where('setting_group_id', 2)->select();
        foreach ($data as $k=>$v){
            foreach ($v['content'] as $kk=>$vv){
                $arr[$vv['field']] = $vv['content'];
            }
        }
        return $arr;
    }

    //导航
    public function nav()
    {
        $data = ArticleCategory::where(['parent_id'=>0])->order('sort','asc')->field('id,parent_id,name')->select();
        if(!is_null($data)){
            $data = $data->toArray();
        }
        return $this->get_authTree($data);
    }

    //导航--递归
    public function get_authTree($data,$pid=0,$level=0)
    {
        $arr = [];
        foreach($data as $key=>$value){
            if($value['parent_id'] == $pid){
                $value['level'] = $level;
                $data_new = ArticleCategory::where(['parent_id'=>$value['id']])->order('sort','asc')->field('id,parent_id,name')->select();
                if(!is_null($data)){
                    $data_new = $data_new->toArray();
                }
                $value['son'] = $this->get_authTree($data_new,$value['id'],$level+1);
                $arr[] = $value;
            }
        }
        return $arr;
    }

}
