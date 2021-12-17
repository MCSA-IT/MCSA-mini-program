<?php
namespace app\api\controller;

use PHPExcel;
use PHPExcel_IOFactory;
use think\Controller;
use think\facade\Session;
use think\Request;
use think\Db;
use think\facade\Config;
use EasyWeChat\Factory;
use app\common\model\Users;

class BaseController extends Controller
{
    protected $app = null;
    protected $request;
    protected $wechatUser = [];

    public function __construct()
    {
        parent::__construct();
        $this->app = Factory::officialAccount(Config::get('api.wechat'));
        if (!Session::has('wechat_user')) {
            $targetUrl = $this->request->url(true);
            Session::set('target_url',$targetUrl);
            return $this->app->oauth->redirect()->send();
        }
        $this->wechatUser = Session::get('wechat_user');
        var_dump($this->wechatUser);die;
    }
}
