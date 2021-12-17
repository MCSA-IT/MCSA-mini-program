<?php
/**
 * 轮播模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebBanner extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_banner';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];


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
    

    
}
