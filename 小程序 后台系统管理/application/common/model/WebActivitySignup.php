<?php
/**
 * 活动报名模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebActivitySignup extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_activity_signup';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = ['activity_id'];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    public function user()
    {
        return $this->hasOne('User','id','user_id');
    }

    public function activity()
    {
        return $this->hasOne('WebActivity','id','activity_id');
    }

    
}
