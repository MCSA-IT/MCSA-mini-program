<?php

namespace app\api\controller;

use app\common\model\User;
use app\common\model\WebActivity;
use app\common\model\WebActivitySignup;
use app\common\model\WebBanner;
use app\common\model\WebGuide;
use app\common\model\WebMemberCard;

class IndexController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        $data = [];
        //新闻轮播
        $data['banner'] = WebBanner::field('id,title,url,img,sort')->order(['sort'=>'desc','id'=>'desc'])->all();
        //指南
        $data['guide'] = WebGuide::field('id,title,url')->get(1);
        //活动
        $where[] = ['status','=','1'];
        $where[] = ['state','=','1'];
        $data['activity'] = WebActivity::where($where)->field('id,title,des,img,create_time')->select();

        $this->success($data);
    }

    /**
     * 活动--列表
     */
    public function search()
    {
        $where = [];
        $where[] = ['status','=','1'];
        $list = WebActivity::where($where)
            ->order('id','desc')
            ->paginate($this->limit,true,['page'=>$this->page]);
        $count = WebActivity::where($where)->count();
        $data['page'] = $this->page;
        $data['limit'] = $this->limit;
        $data['total'] = ceil($count/$this->limit);
        $data['data'] = [];
        if(!is_null($list)){
            $list = $list->toArray();
            $data['data'] = $list['data'];
        }
        return $this->success($data);
    }

    /**
     * 活动详情
     */
    public function detail($id)
    {
        $info = WebActivity::get($id);
        //是否报名
        if($this->uid==0){
            $info['is_signup'] = 0;
        }else{
            $where[] = ['activity_id','=',$id];
            $where[] = ['user_id','=',$this->uid];
            $is_signup = WebActivitySignup::where($where)->count();
            $info['is_signup'] = $is_signup==0?0:1;
        }
        if(!is_null($info)){
            return $this->success($info);
        }
        return $this->error("查无此信息");
    }

    /**
     * 指南、关于学联
     */
    public function info()
    {
        $data = [];
        //关于学联
        $data['about'] = WebGuide::field('id,title,url')->get(2);
        //指南
        $data['guide'] = WebGuide::field('id,title,url')->get(1);
        $this->success($data);
    }
}