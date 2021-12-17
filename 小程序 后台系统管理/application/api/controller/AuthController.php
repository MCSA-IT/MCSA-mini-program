<?php


namespace app\api\controller;

use app\api\traits\Retroactive;
use app\common\model\AlbumText;
use app\common\model\ClockinGroup;
use app\common\model\ClockinList;
use app\common\model\Holiday;
use app\common\model\Setting;
use app\common\model\User;
use app\common\model\Fans;
use app\common\model\RetroactiveUser;
use app\common\model\RetroactiveCard;
use app\common\model\WebAbout;
use app\common\model\WebAnnouncement;
use app\common\model\WebForum;
use app\common\model\WebNews;
use app\common\model\WebProduct;
use app\common\model\Week;
use EasyWeChat\Factory;
use think\facade\Config;
use think\facade\Cache;

class AuthController extends Controller
{
    //授权
    public function oauth()
    {
        $app = Factory::miniProgram(Config::get("api.easywechat"));
        $pid = isset($this->param['pid'])?intval($this->param['pid']):0;
        //接收code,获取openid，返回给小程序token
        if(isset($this->param['code'])&&!empty($this->param['code'])&&isset($this->param['avatarUrl'])&&isset($this->param['nickName'])&&isset($this->param['gender'])&&isset($this->param['city'])&&isset($this->param['country'])&&isset($this->param['province'])){
            $user = $app->auth->session($this->param['code']);
            if(!isset($user['errcode'])){
                $openid = $user['openid'];
                $session_key = $user['session_key'];
                //插入数据库、并验证是否注册
                $user_all = $this->get_user($openid,$this->param['avatarUrl'],$this->param['nickName'],$this->param['gender'],$this->param['city'],$this->param['country'],$this->param['province'],$pid);
                if($user_all){
                    $token = md5(rand(10,1000).$user_all['user_id'].$session_key);
                    //设置token缓存
                    $tokencache=[
                        'uid'=>$user_all['user_id']
                        ,'mobile'=>$user_all['mobile']
                        ,'token'=>$token
                        ,'openid'=>$openid
                        ,'session_key'=>$session_key
                    ];
                    Cache::set($token,$tokencache,86400);
                    $data = ['token'=>$token,'mobile'=>$user_all['mobile']];
                    $this->success($data);
                }
            }
            $this->error('授权失败');
        }
        $this->error('获取openid失败，code或用户名过期');
    }
    //判断是否已注册
    private function get_user($openid,$avatar,$nickname,$gender,$city,$country,$province,$pid)
    {
        $user_result = User::get(['openid'=>$openid]);
        //注册插入数据
        $data = false;
        if(!$user_result){
            $user = new User;
            $user->openid     = $openid;
            $user->username     = $nickname;
            $user->nickname     = $nickname;
            $user->avatar     = $avatar;
            $user->gender     = $gender;
            $user->city     = $city;
            $user->country     = $country;
            $user->province     = $province;
            $user->create_time    = time();
            if(!$user->save()){
                return false;
            }
            $user_id = $user->id;
            $mobile = '';
        }else{
            $user_id = $user_result['id'];
            $mobile = $user_result['mobile'];
        }
        return ['user_id'=>$user_id,'mobile'=>$mobile];

//        $forum = WebForum::with('user,forumhf')->select();
//        halt($forum);
    }
}