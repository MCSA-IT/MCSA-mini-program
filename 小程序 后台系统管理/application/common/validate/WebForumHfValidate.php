<?php
/**
 * 评论验证器
 */

namespace app\common\validate;

class WebForumHfValidate extends Validate
{
    protected $rule = [
            'forum_id|帖子id' => 'require',
    'content|内容' => 'require',

    ];

    protected $message = [
            'forum_id.require' => '帖子id不能为空',
    'content.require' => '内容不能为空',

    ];

    protected $scene = [
        'add'  => ['forum_id','content',],
'edit' => ['forum_id','content',],

    ];

    

}
