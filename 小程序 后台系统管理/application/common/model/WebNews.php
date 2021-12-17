<?php
/**
 * 新闻资讯模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebNews extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_news';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    

    

    
}
