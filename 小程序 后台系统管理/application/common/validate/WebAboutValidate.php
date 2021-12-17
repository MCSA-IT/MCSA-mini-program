<?php
/**
 * 关于我们验证器
 */

namespace app\common\validate;
class WebAboutValidate extends Validate
{
    protected $rule = [
        'title|标题' => 'require',
        'ms|描述' => 'require',
        'content|博物馆简介' => 'require',
        'content1|人物简介' => 'require',
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'ms.require' => '描述不能为空',
        'content.require' => '博物馆简介不能为空',
        'content1.require' => '人物简介不能为空',
    ];
    protected $scene = [
        'add' => ['title', 'ms','content', 'content1',],
        'edit' => ['title','ms', 'content', 'content1',],
    ];
}
