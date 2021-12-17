<?php


namespace app\api\controller;

use app\common\model\User;
use app\common\validate\UserValidate;
use Exception;
use think\facade\Cache;
use think\Request;
use think\response\Json;
use EasyWeChat\Factory;
use think\facade\Config;

class ApiController extends Controller
{
    protected $uid = 0;//当前访问的用户
    protected $session_key;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        //验证token
        if(isset($this->param['token'])&&!empty($this->param['token'])){
            $token = $this->param['token'];//接收的token
            $tokencache = cache($token);//缓存的token数据
            if(!isset($tokencache['token'])||empty($tokencache['token'])||$token!=$tokencache['token']||$tokencache['uid']==""){
                $this->error_token("token失效,服务器token为空");
            }
            $user_check = User::get($tokencache['uid']);
            if(is_null($user_check)){
                $this->error_token("token失效");
            }
            $this->uid = $tokencache['uid'];
            $this->session_key = isset($tokencache['session_key'])?$tokencache['session_key']:$this->error_token('token失效,未接收到token');
        }else{
            $this->error_token('token失效,未接收到token');
        }
        //$this->uid = 5;
    }

    //文字验证
    function check($data_arr)
    {
        $app = Factory::miniProgram(Config::get("api.easywechat"));
        foreach ($data_arr as $k=>$v){
            $result = $app->content_security->checkText($v);
            if($result['errcode']!=0){
                return $k;
                return false;
            }
        }
        return true;
    }
}