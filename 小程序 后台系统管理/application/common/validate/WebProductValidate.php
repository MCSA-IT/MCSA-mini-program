<?php
/**
 * 产品验证器
 */

namespace app\common\validate;

class WebProductValidate extends Validate
{
    protected $rule = [
            'code|溯源码' => 'require',
    'ms|描述' => 'require',
    'note|备注' => 'require',
    'ewm|二维码' => 'require',

    ];

    protected $message = [
            'code.require' => '溯源码不能为空',
    'ms.require' => '描述不能为空',
    'note.require' => '备注不能为空',
    'ewm.require' => '二维码不能为空',

    ];

    protected $scene = [
        'add'  => ['code','ms',],
'edit' => ['code','ms',],

    ];

    

}
