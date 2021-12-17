<?php


namespace app\api\controller;

use app\common\model\Attachment;
use app\common\model\User;
use app\common\model\WebProduct;
use EasyWeChat\Factory;
use think\facade\Config;
use think\Request;

class CommonController extends ApiController
{
    /**
     * 电话解密
     */
    public function gettel()
    {
        $user = User::get($this->uid);
        //电话号码解密
        if(empty($user['mobile'])){
            $iv = isset($this->param['iv'])?$this->param['iv']:$this->error("iv为空");
            $encryptedData = isset($this->param['encryptedData'])?$this->param['encryptedData']:$this->error("encryptedData为空");
            $app = Factory::miniProgram(Config::get("api.easywechat"));
            $decryptedData = $app->encryptor->decryptData($this->session_key, $iv, $encryptedData);
            $mobile = isset($decryptedData['phoneNumber'])?$decryptedData['phoneNumber']:$this->error("电话号码解密失败");
            $user->mobile = $mobile;
            if($user->save()){
                return $this->success($user);
            }else{
                return $this->error();
            }
        }
        return $this->success();
    }

    /**
     * 我的-修改
     */
    public function save()
    {
        $param = $this->param;
        if($this->request->isPost()){
            if(!isset($param['id'])){
                $this->error('id不存在');
            }
            $check = $this->check($param);//验证
            if(!$check){
                $this->error('内容违规');
            }
            $data = WebProduct::get($param['id']);
            unset($param['id']);
            $param['img_arr'] = explode(',',$param['img_arr']);
            if($data->save($param)){
                return $this->success();
            }
            return $this->error();
        }
        if(!isset($param['code'])){
            $this->error('code不存在');
        }
        $info = WebProduct::where('code',$param['code'])->field('id,code,img_arr,ms,create_time')->find();
        if(is_null($info)){
            return $this->error('查无此信息');
        }
        return $this->success($info);
    }

    /**
     * 图片上传
     */
    public function image(Request $request)
    {
        $app = Factory::miniProgram(Config::get("api.easywechat"));
        //处理图片上传
        $attachment_avatar = new Attachment;
        $file_avatar       = $attachment_avatar->upload('image');
        if (isset($file_avatar->url)) {
            $file_avatar_new = imgUrl($file_avatar->url);
            $checkimage_file_avatar = '.'.$file_avatar->url;
            $result = $app->content_security->checkImage($checkimage_file_avatar);
            if($result['errcode']==0){
                return $this->success($file_avatar_new);
            }
            return $this->error('图片违规');
        } else {
            return $this->error($attachment_avatar->getError());
        }
    }

}