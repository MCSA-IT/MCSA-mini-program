<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\common\model\ArticleCategory;
use think\facade\Config;

if (!function_exists('remove_imgUrl')) {
    /**
     * @param str $str 图片地址  去掉域名
     * @return str
     */
    function remove_imgUrl($str = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        $http_str = $protocol . $domainName;
        $data = str_replace($http_str,'',$str);
        return $data;
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
//详情图片地址
if (!function_exists('xqimgUrl')) {
    /**
     * @param str $str 图片地址
     * @return str
     */
    function xqimgUrl($str = '')
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $str.$protocol . $domainName;
    }
}
function cata($cata_id){
    $cata_name = ArticleCategory::where(['id'=>$cata_id])->value('name');
    return isset($cata_name)?$cata_name:'';
}
if (!function_exists('get_middle_str')) {
    /**
     * 获取两个字符串中间的字符
     * @param $str
     * @param $leftStr
     * @param $rightStr
     * @return bool|string
     */
    function get_middle_str($str, $leftStr, $rightStr)
    {
        $left  = strpos($str, $leftStr);
        $right = strpos($str, $rightStr, $left);
        if ($right < $left || $left < 0) {
            return '';
        }
        return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
    }
}


if (!function_exists('format_size')) {
    /**
     * 格式化文件大小单位
     * @param $size
     * @param string $delimiter
     * @return string
     */
    function format_size($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}


if (!function_exists('setting')) {
    /**
     * 设置相关助手函数
     * @param string $name
     * @param null $value
     * @return array|bool|mixed|null
     */
    function setting($name = '', $value = null)
    {
        if ($value === null && is_string($name)) {
            //获取一级配置
            if ('.' === substr($name, -1)) {
                $result = Config::pull(substr($name, 0, -1));
                if (count($result) == 0) {
                    //如果文件不存在，查找数据库
                    $result = get_database_setting(substr($name, 0, -1));
                }

                return $result;
            }
            //判断配置是否存在或读取配置
            if (0 === strpos($name, '?')) {
                $result = Config::has(substr($name, 1));
                if (!$result) {
                    //如果配置不存在，查找数据库
                    if ($name && false === strpos($name, '.')) {
                        return [];
                    }

                    if ('.' === substr($name, -1)) {

                        return get_database_setting(substr($name, 0, -1));
                    }

                    $name    = explode('.', $name);
                    $name[0] = strtolower($name[0]);

                    $result = get_database_setting($name[0]);
                    if ($result) {
                        $config = $result;
                        // 按.拆分成多维数组进行判断
                        foreach ($name as $val) {
                            if (isset($config[$val])) {
                                $config = $config[$val];
                            } else {
                                return null;
                            }
                        }

                        return $config;

                    }
                    return $result;
                }

                return $result;
            }

            $result = Config::get($name);
            if (!$result) {
                $result = get_database_setting($name);
            }
            return $result;
        }
        return Config::set($name, $value);
    }

}

if (!function_exists('get_database_setting')) {
    function get_database_setting($name)
    {
        $result = [];
        $group  = \app\common\model\SettingGroup::where('code', $name)->find();
        if ($group) {
            $result = [];
            foreach ($group->setting as $key => $setting) {
                $key_setting = [];
                foreach ($setting->content as $content) {
                    $key_setting[$content['field']] = $content['content'];
                }
                $result[$setting->code] = $key_setting;
            }
        }

        return $result;
    }
}

/**
 * 异步 执行程序
 * @param string $path 异步url 地址
 * @param array $postData 传递的参数
 * @param string $method 请求方式
 * @param string $url 请求地址
 * @return bool
 */
function request_asynchronous($path, $method = "POST", $postData = array(), $url = ''){
    if(empty($path)){
        return false;
    }
    if($url) {
        $matches = parse_url($url);
        $host = $matches['host'];
        //$path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        if ($matches['scheme'] == 'https') { //判断是否使用HTTPS
            $transports = 'ssl://';  //如使用HTTPS则使用SSL协议
            $port = !empty($matches['port']) ? $matches['port'] : 443; //如使用HTTPS端口使用443
        } else {
            $transports = 'tcp://'; //如没有使用HTTPS则使用tcp协议
            $port = !empty($matches['port']) ? $matches['port'] : 80;//如没有使用HTTPS则使用80端口
        }
    }else{
        $port = 443;
        $transports = 'ssl://';
        $host = $_SERVER['HTTP_HOST'];
    }

    $errNo = 0;
    $errStr = '';
    $timeout = 60;
    $fp = '';
    if(function_exists('fsockopen')) {
        $fp = @fsockopen(($transports . $host), $port, $errno, $errStr, $timeout);
    } elseif(function_exists('pfsockopen')) {
        $fp = @pfsockopen($transports.$host, $port, $errNo, $errStr, $timeout);
    } elseif(function_exists('stream_socket_client')) {
        $fp = @stream_socket_client($transports.$host.':'.$port, $errNo, $errStr, $timeout);
    }

    if (!$fp) {
        return false;
    }

    stream_set_blocking($fp, 0); //开启非阻塞模式
    stream_set_timeout($fp,  3); //设置超时时间（s）

    $date = [];
    if($postData) {
        //处理文件
        foreach ($postData as $key => $value) {
            if(is_array($value)){
                $date[$key] = serialize($value);
            }else{
                $date[$key] = $value;
            }
        }
    }

    if ($method == "GET") {
        $query = $date ? http_build_query($date) : '';
        $path .= "?".$query;
    }else{
        $query = json_encode($date);
    }

    //$cookie = "token={$_COOKIE['token']}; session={$_COOKIE['session']}";
    $cookie = 1;
    //http消息头
    $out = $method." ".$path." HTTP/1.1\r\n";
    $out .= "HOST: ".$host."\r\n";
    if ($method == "POST") {
        $out .= "Content-Length:".strlen($query)."\r\n";
    }
    $out .= "Accept: application/json, text/plain, */*\r\n";
    $out .= "Access-Control-Allow-Credentials: true\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Cookie: ".$cookie."\r\n";
    $out .= "Connection: Close\r\n\r\n";
    if ($method == "POST") {
        $out .= $query;
    }

    fputs($fp, $out);
    usleep(500);
    //忽略执行结果
    /*while (!feof($fp)) {
        echo fgets($fp, 128);
    }*/
    fclose($fp);

    return true;
}

//评论几天前、几小时前
function formatTime($date) {
    $str = '';
    $timer = strtotime($date);
    $diff = $_SERVER['REQUEST_TIME'] - $timer;
    $day = floor($diff / 86400);
    $free = $diff % 86400;
    if($day > 0) {
        return $day."天前";
    }else{
        if($free>0){
            $hour = floor($free / 3600);
            $free = $free % 3600;
            if($hour>0){
                return $hour."小时前";
            }else{
                if($free>0){
                    $min = floor($free / 60);
                    $free = $free % 60;
                    if($min>0){
                        return $min."分钟前";
                    }else{
                        if($free>0){
                            return $free."秒前";
                        }else{
                            return '刚刚';
                        }
                    }
                }else{
                    return '刚刚';
                }
            }
        }else{
            return '刚刚';
        }
    }
}
//评论几天前、几小时前
function time_tran($the_time){
    $now_time = date("Y-m-d H:i:s",time()+8*60*60);
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if($dur < 0){
        return $the_time;
    }else{
        if($dur < 60){
            return $dur.'秒前';
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前';
            }else{
                if($dur < 86400){
                    return floor($dur/3600).'小时前';
                }else{
                    if($dur < 259200){//3天内
                        return floor($dur/86400).'天前';
                    }else{
                        return $the_time;
                    }
                }
            }
        }
    }
}
//评论几天前、几小时前---目前用这个
function format_date($time)
{
    $t = time() - $time;
    $f = array(
        '31536000' => '年',
        '2592000' => '个月',
        '604800' => '星期',
        '86400' => '天',
        '3600' => '小时',
        '60' => '分钟',
        '1' => '秒'
    );
    foreach ($f as $k => $v) {
        if (0 != $c = floor($t / (int)$k)) {
            return $c . $v . '前';
        }
    }
}
//评论几天前、几小时前
function time_tran_one($the_time)
{
    $now_time = date("Y-m-d H:i:s", time() + 8 * 60 * 60);
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}
