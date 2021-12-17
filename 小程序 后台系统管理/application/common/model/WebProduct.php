<?php
/**
 * 产品模型
*/

namespace app\common\model;

use think\exception\HttpResponseException;
use think\model\concern\SoftDelete;

class WebProduct extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_product';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = ['code'];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    //多图上传获取器
    public function getImgArrAttr($value)
    {
        $data = [];
        if(!empty($value)){
            $arr = json_decode($value, true);
            foreach ($arr as $k=>$v){
                $data[] = imgUrl($v);
            }
        }
        return $data;
    }

    //多图上传修改器
    public function setImgArrAttr($value)
    {
        $data = [];
        if(!empty($value)){
            //$arr = json_encode($value, true);
            foreach ($value as $k=>$v){
                $data[] = remove_imgUrl($v);
            }
        }
        return json_encode($data);
    }
    //获取code溯源码
    static function get_code($id)
    {
        $model = self::where('id',$id)->column('code');
        if($model){
            return $model[0];
        }
        return '';
    }
    //更新
    static function set_ewm($id,$ewm)
    {
        $user = self::get($id);
        $user->ewm     = $ewm;
        if($user->save()){
            return true;
        }
        return false;
    }

    static function set_ewm_all($data)
    {
        $user = new WebProduct;
        if($user->saveAll($data)){
            return true;
        }
        return false;
    }
    //创建新数据 $num生成数量 小于50
    static function create_code($num)
    {
        $code_arr = [];
        $arr = [];
        if($num>5000){
            return false;
        }
        for ($i=1;$i<=$num;$i++){
            $u = new WebProduct();
            $code = $u->create_invite_code($arr);
            $arr[] = $code;
            $code_arr[] = ['code'=>$code];//生成的溯源码
        }
        $product_model = new WebProduct;
        $result = $product_model->saveAll($code_arr);
        //throw new HttpResponseException($result);
        if($result){
            return true;
        }
        return false;
    }

    //生成溯源码
    protected function create_invite_code($arr){
        $d = substr(base_convert(md5(uniqid(md5(microtime(true)),true)), 16, 10), 0, 9);
        if(in_array($d,$arr)){
            $this->create_invite_code($arr);
        }else{
            $w['code'] = array('eq', $d);
            $user_info = self::field("id")->where($w)->find();
            if ($user_info) {
                $this->create_invite_code($arr);
            }
        }
        return $d;
    }
}
