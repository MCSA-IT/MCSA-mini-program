<?php
/**
 * 商家验证器
 */

namespace app\common\validate;

class WebMerchantValidate extends Validate
{
    protected $rule = [
            'title|标题' => 'require',
    'tel|电话' => 'require',
    'wx|微信' => 'require',
    'content|内容' => 'require',
    'addr|地址' => 'require',

    ];

    protected $message = [
            'title.require' => '标题不能为空',
    'tel.require' => '电话不能为空',
    'wx.require' => '微信不能为空',
    'content.require' => '内容不能为空',
    'addr.require' => '地址不能为空',

    ];

    protected $scene = [
        'add'  => ['title','tel','wx','content','addr',],
'edit' => ['title','tel','wx','content','addr',],

    ];

    

}
