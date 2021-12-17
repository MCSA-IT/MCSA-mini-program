<?php
/**
 * 新闻资讯验证器
 */

namespace app\common\validate;

class WebNewsValidate extends Validate
{
    protected $rule = [
            'title|标题' => 'require',
    'content|内容' => 'require',

    ];

    protected $message = [
            'title.require' => '标题不能为空',
    'content.require' => '内容不能为空',

    ];

    protected $scene = [
        'add'  => ['title','content',],
'edit' => ['title','content',],

    ];

    

}
