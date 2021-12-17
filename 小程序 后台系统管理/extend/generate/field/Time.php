<?php
/**
 * 时间
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Time extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <input id="[FIELD_NAME]" readonly name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请选择[FORM_NAME]" type="text" class="form-control filed-time">
    </div>
</div>
<script>
    laydate.render({
        elem: '#[FIELD_NAME]',
        type: 'time',
    });
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'time'     => '时间',
        'regular'  => '自定义正则'
    ];


    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}