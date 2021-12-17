<?php
/**
 * 论坛验证器
 */

namespace app\common\validate;

class WebForumValidate extends Validate
{
    protected $rule = [
            'user_id|用户' => 'require',
    'content|内容' => 'require',

    ];

    protected $message = [
            'user_id.require' => '用户不能为空',
    'content.require' => '内容不能为空',

    ];

    protected $scene = [
        'add'  => ['user_id','content',],
'edit' => ['user_id','content',],

    ];

    

}
