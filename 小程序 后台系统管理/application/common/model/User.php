<?php
/**
 * 用户模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class User extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'user';
    protected $autoWriteTimestamp = true;
    protected $append = ['email'];

    //可搜索字段
    protected $searchField = ['username', 'mobile', 'nickname'];

    public function card()
    {
        return $this->hasOne('WebMemberCard','id','user_id');
    }

    public function getEmailAttr($value,$data)
    {
        $email = WebMemberCard::where('user_id',$data['id'])->value('email');
        if($email){
            return $email;
        }
        return '';
    }

    //论坛查询 用户id  $field_arr字段数组  $keyword关键词
    static function get_user_arr($field_arr,$keyword)
    {
        $user_id_arr = [];
        foreach ($field_arr as $k=>$v){
            $where = [];
            $where[] = [$v,'like','%'.$keyword.'%'];
            $user_id = self::where($where)->column('id');
            if($user_id){
                $user_id_arr = array_merge($user_id_arr,$user_id);
            }
        }
        return array_unique($user_id_arr);
    }
}
