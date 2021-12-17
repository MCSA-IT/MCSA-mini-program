<?php

use app\common\model\ClockinList;
use think\facade\Config;
use think\response\Json;


if (!function_exists('clockin_time_type')) {
    /**
     * @return array 返回时间类型  四个时间段的类型  早打卡、中午、晚打卡、其他
     * $data['type'] 打卡类型 早打卡  晚打卡
     * $data['date']  打卡时间  如果是凌晨  则显示上一天的、
     * 查询是否打卡  用到 type/date/user_id
     */
    function clockin_time_type()
    {
        //打卡时间段
        $get_checkin_time = ClockinList::get_checkin_time();
        $morning_start = $get_checkin_time['morning_start'];
        $morning_end = $get_checkin_time['morning_end'];
        $evening_start = $get_checkin_time['evening_start'];
        $evening_end = $get_checkin_time['evening_end'];
        //判断状态
        //$time = date('H:i');
        $time = time();//当前打卡时间
        $date = date('Y-m-d',$morning_start);

        if($time>=$morning_start&&$time<=$morning_end){
            $data['class'] = 'morning';
            $data['type'] = 'morning';
            $data['date'] = $date;
        }elseif($time>=$evening_start&&$time<=$evening_end){
            $data['class'] = 'evening';
            $data['type'] = 'evening';
            $data['date'] = $date;
        }elseif($time>$morning_end&&$time<$evening_start){
            $data['class'] = 'noon';
            $data['type'] = 'morning';
            $data['date'] = $date;
        }else{
            $morning_start_new = strtotime($morning_start);
            $date_new = strtotime($date);
            if($date_new<$morning_start_new){
                $date = date("Y-m-d",strtotime("-1 day"));
            }
            $data['class'] = 'other';
            $data['type'] = 'evening';
            $data['date'] = $date;
        }
        return $data;
    }
}
if (!function_exists('addUrl')) {
    /**
     * @param array $array 要处理的数组
     * @param array $fields 要处理的字段名
     * @return array
     */
    function addUrl($array = [], $fields = [])
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        
        if (!empty($array) && !empty($fields)) {
            foreach ($array as &$v1) {
                foreach ($fields as &$v2) {
                    $v1[$v2] = $protocol . $domainName . $v1[$v2];
                }
            }
            return $array;
        }
    }
}
if (!function_exists('imgUrl')) {
    /**
     * @param str $str 图片地址
     * @return str
     */
    function imgUrl($str = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName . $str;
    }
}

if (!function_exists('success')) {
    /**
     * 操作成功
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function success($data = '', $msg = 'success', $code = 200)
    {
        return result($msg, $data, $code);
    }
}

if (!function_exists('error')) {
    /**
     * 操作失败
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function error($msg = 'fail', $data = '', $code = 500)
    {
        return result($msg, $data, $code);
    }
}

if (!function_exists('result')) {
    /**
     * 返回json结果
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function result($msg = 'fail', $data = '', $code = 500)
    {
        $header = [];
        //处理跨域请求问题
        if (config('api.cross_domain.allow')) {
            $header = ['Access-Control-Allow-Origin' => '*'];
            if (request()->isOptions()) {
                $header = config('api.cross_domain.header');
                return json('', 200, $header);
            }
        }

        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ], $code, $header);
    }
}

if (!function_exists('unauthorized')) {
    /**
     * 未授权
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function unauthorized($msg = 'unauthorized', $data = '', $code = 401)
    {
        return result($msg, $data, $code);
    }
}

if (!function_exists('client_error')) {
    /**
     * 客户端错误
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function client_error($msg = 'client error', $data = '', $code = 400)
    {
        return result($msg, $data, $code);
    }
}

if (!function_exists('server_error')) {
    /**
     * 服务端错误
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function server_error($msg = 'server error', $data = '', $code = 500)
    {
        return result($msg, $data, $code);
    }
}

if (!function_exists('error_404')) {
    /**
     * 资源或接口不存在
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function error_404($msg = '404 not found', $data = '', $code = 404)
    {
        return result($msg, $data, $code);
    }
}


//时间转秒
function to_seconds($time)
{
    $parsed = date_parse($time);
    $seconds = $parsed['hour'] * 3600+$parsed['minute'] * 60;
    return $seconds;
}
//时间转秒
function to_time($time)
{
    $h=floor($time/3600);
    if($h<10){$h='0'.$h;}
    $t=$time%3600;
    $m=ceil($t/60);
    if($m<10){$m='0'.$m;}
    return $h.':'.$m;
}