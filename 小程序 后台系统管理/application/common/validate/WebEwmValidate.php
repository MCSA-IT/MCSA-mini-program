<?php
/**
 * 二维码验证器
 */

namespace app\common\validate;

class WebEwmValidate extends Validate
{
    protected $rule = [
            'title|标题' => 'require',

    ];

    protected $message = [
            'title.require' => '标题不能为空',

    ];

    protected $scene = [
        'add'  => ['title',],
'edit' => ['title',],

    ];

    

}
