<?php
/**
 * 活动报名验证器
 */

namespace app\common\validate;

class WebActivitySignupValidate extends Validate
{
    protected $rule = [
            'activity_id|活动id' => 'require',
    'user_id|用户id' => 'require',

    ];

    protected $message = [
            'activity_id.require' => '活动id不能为空',
    'user_id.require' => '用户id不能为空',

    ];

    protected $scene = [
        'add'  => ['activity_id','user_id',],
'edit' => ['activity_id','user_id',],

    ];

    

}
