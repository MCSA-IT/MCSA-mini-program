<?php
/**
 * 轮播验证器
 */

namespace app\common\validate;

class WebBannerValidate extends Validate
{
    protected $rule = [
            'title|标题' => 'require',
    'url|外链' => 'require',

    ];

    protected $message = [
            'title.require' => '标题不能为空',
    'url.require' => '外链不能为空',

    ];

    protected $scene = [
        'add'  => ['title','url',],
'edit' => ['title','url',],

    ];

    

}
