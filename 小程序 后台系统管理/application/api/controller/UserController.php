<?php


namespace app\api\controller;

use app\common\model\User;
use app\common\model\WebGuide;
use app\common\model\WebMemberCard;
use app\common\validate\WebMemberCardValidate;
use think\Request;

class UserController extends ApiController
{
    /**
     * 用户信息
     */
    public function info()
    {
        $data = [];
        //用户信息
        $data['info'] = User::get($this->uid);
        //会员卡
        $data['card'] = WebMemberCard::where('user_id',$this->uid)->find();

        $this->success($data);
    }

    /**
     * 会员信息提交
     */
    public function save(Request $request, WebMemberCardValidate $validate)
    {
        $param = $this->param;
        $param           = $request->param();
        $validate_result = $validate->scene('wap')->check($param);
        if (!$validate_result) {
            return $this->error($validate->getError());
        }
        $check = $this->check($param);//验证
        if(!$check){
            $this->error($check.'内容违规');
            $this->error('内容违规');
        }
        $param['user_id'] = $this->uid;
        $data = WebMemberCard::create($param);
        if($data){
            return $this->success();
        }
        return $this->error();

    }
}