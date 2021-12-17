<?php
/**
 * 会员卡模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebMemberCard extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_member_card';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = ['status'];

    //可做为时间
    protected $timeField = [];

    //审核  0待审核  1审核通过获取器
    public function getStatusTextAttr($value, $data)
    {
        $arr = [
            '待审核',
            '审核通过'
        ];
        return $arr[$data['status']];
    }


    public function user()
    {
        return $this->hasOne('User','id','user_id');
    }
    //图上传获取器
    public function getImgAttr($value)
    {
        $data = '';
        if(!empty($value)){
            $data = imgUrl($value);
        }
        return $data;
    }

    //图上传修改器
    public function setImgAttr($value)
    {
        $data = '';
        if(!empty($value)){
            $data = remove_imgUrl($value);
        }
        return $data;
    }

    //论坛查询 用户id  $field_arr字段数组  $keyword关键词
    static function get_user_arr($field_arr,$keyword)
    {
        $user_id_arr = [];
        foreach ($field_arr as $k=>$v){
            $where = [];
            $where[] = [$v,'like','%'.$keyword.'%'];
            $user_id = self::where($where)->column('user_id');
            if($user_id){
                $user_id_arr = array_merge($user_id_arr,$user_id);
            }
        }
        return array_unique($user_id_arr);
    }
    
}
