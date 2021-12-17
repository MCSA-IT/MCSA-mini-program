<?php
/**
 * 关于我们模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class WebAbout extends Model
{
    use SoftDelete;

    public $softDelete = true;
    protected $name = 'web_about';
    protected $autoWriteTimestamp = true;
    //可搜索字段
    protected $searchField = [];
    //可作为条件的字段
    protected $whereField = [];
    //可做为时间
    protected $timeField = [];

    //多视频上传获取器
//    public function getVideoAttr($value)
//    {
//        return json_decode($value, true);
//    }
    public function getVideoAttr($value)
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

//多视频上传修改器
    public function setVideoAttr($value)
    {
        $data = [];
        if(!empty($value)){
            //$arr = json_decode($value, true);
            foreach ($value as $k=>$v){
                $data[] = remove_imgUrl($v);
            }
        }
        if(!empty($data)){
            return json_encode($data);
        }
        return '';
    }

//多图片上传获取器
//    public function getImgAttr($value)
//    {
//        return json_decode($value, true);
//    }
    public function getImgAttr($value)
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

//多图片上传修改器
    public function setImgAttr($value)
    {
        $data = [];
        if(!empty($value)){
            //$arr = json_decode($value, true);
            foreach ($value as $k=>$v){
                $data[] = remove_imgUrl($v);
            }
        }
        if(!empty($data)){
            return json_encode($data);
        }
        return '';
    }
}
