<?php
/**
 * 图标模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class AdminImg extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'admin_img';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    public function getImgAttr($value,$data){
        if(!empty($data['img'])){
            return imgUrl($data['img']);
        }
        return $data['img'];
    }

    

    
}
