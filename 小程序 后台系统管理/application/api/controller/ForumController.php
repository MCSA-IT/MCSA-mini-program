<?php


namespace app\api\controller;

use app\common\model\Attachment;
use app\common\model\User;
use app\common\model\WebActivitySignup;
use app\common\model\WebAnnouncement;
use app\common\model\WebEwm;
use app\common\model\WebForum;
use app\common\model\WebForumHf;
use app\common\model\WebMemberCard;
use app\common\model\WebProduct;
use EasyWeChat\Factory;
use think\facade\Config;
use think\Request;

class ForumController extends ApiController
{
    /**
     * 活动报名
     */
    public function signup()
    {
        $activity_id = isset($this->param['activity_id'])?$this->param['activity_id']:$this->error('activity_id不能为空');
        $where[] = ['activity_id','=',$activity_id];
        $where[] = ['user_id','=',$this->uid];
        $is_signup = WebActivitySignup::where($where)->count();
        if($is_signup==0){
            $WebActivitySignup = WebActivitySignup::create([
                'activity_id'  =>  $activity_id,
                'user_id' =>  $this->uid
            ]);
            if(!$WebActivitySignup){
                return $this->error();
            }
        }
        return $this->success();
    }

    /**
     * 公告--列表disable_state
     */
    public function announcement()
    {
        $where = [];
        $list = WebAnnouncement::where($where)
            ->order(['sort'=>'desc','id'=>'desc'])
            ->paginate($this->limit,true,['page'=>$this->page]);
        $count = WebAnnouncement::where($where)->count();
        $data['page'] = $this->page;
        $data['limit'] = $this->limit;
        $data['total'] = ceil($count/$this->limit);
        $data['data'] = [];
        if(!is_null($list)){
            $list = $list->toArray();
            $data['data'] = $list['data'];
        }
        //二维码
        $data['ewm_list'] = WebEwm::all();
        return $this->success($data);
    }


    /**
     * 论坛--列表
     */
    public function search()
    {
        $where = [];
        if(isset($this->param['keyword'])&&!empty($this->param['keyword'])){
            //查用户表
            $user_field_arr = ['nickname','city','country','province'];
            $user_user_id_arr = User::get_user_arr($user_field_arr,$this->param['keyword']);
            //查userid_arr
            $field_arr = ['name','region','year'];
            $user_id_arr = WebMemberCard::get_user_arr($field_arr,$this->param['keyword']);
            $user_id_arr = array_unique(array_merge($user_user_id_arr,$user_id_arr));
            $where[] = ['user_id','in',$user_id_arr];
        }
        $list = WebForum::where($where)->with('user')
            ->order(['sort'=>'desc','id'=>'desc'])
            ->paginate($this->limit,true,['page'=>$this->page]);
        $count = WebForum::where($where)->count();
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
     * 评论回复列表
     */
    public function search_hf()
    {
        $forum_id = isset($this->param['forum_id'])?$this->param['forum_id']:$this->error("缺少forum_id");
        $where = [];
        $where[] = ['forum_id','=',$forum_id];
        $list = WebForumHf::where($where)->with('user')
            ->order('id','desc')
            ->paginate($this->limit,true,['page'=>$this->page]);
        $count = WebForumHf::where($where)->count();
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
     * 论坛发布
     */
    public function forumsave()
    {
        $param = $this->param;
        if(!isset($param['content'])){
            $this->error('内容不能为空');
        }
        $check = $this->check($param);//验证
        if(!$check){
            $this->error('内容违规');
        }
        $param['user_id'] = $this->uid;
        $data = WebForum::create($param);
        if($data){
            return $this->success();
        }
        return $this->error();
    }

    /**
     * 评论
     */
    public function forumhfsave()
    {
        $param = $this->param;
        if(!isset($param['forum_id'])){
            $this->error('缺少forum_id');
        }
        if(!isset($param['content'])){
            $this->error('内容不能为空');
        }
        $check = $this->check($param);//验证
        if(!$check){
            $this->error('内容违规');
        }
        $param['user_id'] = $this->uid;
        $data = WebForumHf::create($param);
        if($data){
            return $this->success();
        }
        return $this->error();
    }
}