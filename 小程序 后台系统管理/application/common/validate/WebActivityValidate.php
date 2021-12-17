<?php
/**
 * 活动验证器
 */

namespace app\common\validate;

class WebActivityValidate extends Validate
{
    protected $rule = [
            'title|标题' => 'require',
    'des|简介' => 'require',
    'content|内容' => 'require',
    'status|状态：  1上架  0下架' => 'require',
    'state|推荐  1是  0否' => 'require',

    ];

    protected $message = [
            'title.require' => '标题不能为空',
    'des.require' => '简介不能为空',
    'content.require' => '内容不能为空',
    'status.require' => '状态：  1上架  0下架不能为空',
    'state.require' => '推荐  1是  0否不能为空',

    ];

    protected $scene = [
        'add'  => ['title','des','content','status','state',],
'edit' => ['title','des','content','status','state',],

    ];

    

}
