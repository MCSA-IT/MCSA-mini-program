<?php
/**
 * Api基础控制器
 */

namespace app\api\controller;

use app\common\model\User;
use think\facade\Cache;
use think\facade\Session;
use think\Request;
use app\api\traits\ApiAuth;

use think\App;
use think\exception\HttpResponseException;
use think\Response;
use think\Validate;

class Controller
{
    use ApiAuth;
    
    protected $uid = 0;//当前访问的用户
    protected $page;//当前页码
    protected $limit;//每页数据量
    protected $request;
    protected $param;//当前请求的参数，get/post都在其中
    protected $id;//当前请求数据的ID

    public function __construct(Request $request)
    {
        $this->request = $request;
        //初始化基本数据
        $this->param = $request->param();
        $this->page  = $this->param['page'] ?? 1;
        $this->limit = $this->param['limit'] ?? 10;
        $this->id    = $this->param['id']?? 0;
        if(isset($this->param['token'])){
            $tokencache = cache($this->param['token']);//缓存的token数据
            if(isset($tokencache)&&isset($tokencache['uid'])){
                $this->uid = $tokencache['uid'];
                $user_model = User::get($this->uid);
                if(is_null($user_model)){
                    $this->uid = 0;
                }
            }
        }
        //limit防止过大处理
        $this->limit = $this->limit <= 100 ? $this->limit : 100;
    }

    protected function apiResult($msg, $code = 0, $data = null, $type = null, array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'time' => time(),
        ];
        // 如果未设置类型则自动判断
        $type = $type ? $type : ($this->request->param(config('var_jsonp_handler')) ? 'jsonp' : 'json');

        if (isset($header['statuscode'])) {
            $code = $header['statuscode'];
            unset($header['statuscode']);
        } else {
            //未设置状态码,根据code值判断
            $code = $code >= 1000 || $code < 200 ? 200 : $code;
        }
        $response = Response::create($result, $type, $code)->header($header);
        throw new HttpResponseException($response);
    }

    function success($data = '', $msg = 'success')
    {

        $this->apiResult($msg, 0, $data);
    }

    function error($msg = 'error', $data = '')
    {
        $this->apiResult($msg, 1, $data);
    }

    function error_token($msg = 'error', $data = '')
    {
        $this->apiResult($msg, -1, $data);
    }


}
