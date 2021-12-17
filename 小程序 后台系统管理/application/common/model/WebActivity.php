<?php
/**
 * 活动模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebActivity extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'web_activity';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = ['title'];

    //可作为条件的字段
    protected $whereField = ['status','state'];

    //可做为时间
    protected $timeField = [];

    //状态：  1上架  0下架获取器
    public function getStatusTextAttr($value, $data)
    {
        $arr = [
            '下架',
            '上架'
        ];
        return $arr[$data['status']];
    }

    public function getStateTextAttr($value, $data)
    {
        $arr = [
            '否',
            '是'
        ];
        return $arr[$data['state']];
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

    public function getContentAttr($value)
    {
        $str = '<img src="';
        $new = xqimgUrl($str);
        return str_replace($str,$new,$value);
    }

    public function setContentAttr($value)
    {
        $str = '<img src="';
        $new = xqimgUrl($str);
        return str_replace($new,$str,$value);
    }

    
}
