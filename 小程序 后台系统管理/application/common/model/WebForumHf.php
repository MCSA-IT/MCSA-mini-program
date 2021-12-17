<?php
/**
 * 评论模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebForumHf extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_forum_hf';
    protected $autoWriteTimestamp = true;

    protected $append = ['nickname'];
    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    public function user()
    {
        return $this->hasOne('User','id','user_id')->bind('nickname,gender,city,country,province,avatar,mobile,email');
    }

    public function forum()
    {
        return $this->hasOne('WebForum','id','forum_id');
    }

//    public function getNicknameAttr($value,$data)
//    {
//        $nickname = User::where('id',$data['user_id'])->value('nickname');
//        return !is_null($nickname)?$nickname:'匿名';
//    }

    //评论时间
    public function getCreateTimeAttr($value,$data)
    {
        return format_date($data['create_time']);
    }

    
}
