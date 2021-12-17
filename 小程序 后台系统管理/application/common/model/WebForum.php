<?php
/**
 * 论坛模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebForum extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_forum';
    protected $autoWriteTimestamp = true;
    protected $append = ['son_count'];

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

    public function forumhf()
    {
        return $this->hasMany('WebForumHf','forum_id','id');
    }

    //评论时间
    public function getCreateTimeAttr($value,$data)
    {
        return format_date($data['create_time']);
    }

    // 评论总数son_count
    public function getSonCountAttr($value,$data)
    {
        $count = WebForumHf::where('forum_id',$data['id'])->count();
        return $count;
    }

    
}
