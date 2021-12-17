<?php
/**
 * 会员卡验证器
 */

namespace app\common\validate;

class WebMemberCardValidate extends Validate
{
    protected $rule = [
        'img|图片' => 'require',
            'name|姓名' => 'require',
    'birthday|生日' => 'require',
    'discipline|学科' => 'require',
    'identity|身份' => 'require',
    'professional|职业' => 'require',
    'region|地区' => 'require',
    'email|邮箱' => 'require',
    'year|入学年份' => 'require',
    'status|审核  0待审核  1审核通过' => 'require',

    ];

    protected $message = [
        'img.require' => '图片不能为空',
            'name.require' => '姓名不能为空',
    'birthday.require' => '生日不能为空',
    'discipline.require' => '学科不能为空',
    'identity.require' => '身份不能为空',
    'professional.require' => '职业不能为空',
    'region.require' => '地区不能为空',
    'email.require' => '邮箱不能为空',
    'year.require' => '入学年份不能为空',
    'status.require' => '审核  0待审核  1审核通过不能为空',

    ];

    protected $scene = [
        'add'  => ['name','birthday','discipline','identity','professional','region','email','year','status',],
'edit' => ['name','birthday','discipline','identity','professional','region','email','year','status',],
        'wap' => ['img','name','birthday','discipline','identity','email','year'],

    ];

    

}
