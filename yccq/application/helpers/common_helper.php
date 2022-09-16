
<?php



/**
 * 字符截取 支持UTF8/GBK
 *
 * @param
 *            $string
 * @param
 *            $length
 * @param
 *            $dot
 */

function str_cut($string, $length, $dot = '', $charset = 'utf-8')
{

    $strlen = strlen($string);

    if ($strlen <= $length)

        return $string;

    $string = str_replace(

        array(

            ' ',

            '&nbsp;',

            '&amp;',

            '&quot;',

            '&#039;',

            '&ldquo;',

            '&rdquo;',

            '&mdash;',

            '&lt;',

            '&gt;',

            '&middot;',

            '&hellip;'

        ),

        array(

            '∵',

            ' ',

            '&',

            '"',

            "'",

            '"',

            '"',

            '—',

            '<',

            '>',

            '·',

            '…'


        ), $string);

    $strcut = '';

    if ($charset == 'utf-8') {

        $length = intval($length - strlen($dot) - $length / 3);

        $n = $tn = $noc = 0;

        while ($n < strlen($string)) {

            $t = ord($string[$n]);

            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {

                $tn = 1;

                $n++;

                $noc++;

            } elseif (194 <= $t && $t <= 223) {

                $tn = 2;

                $n += 2;

                $noc += 2;

            } elseif (224 <= $t && $t <= 239) {

                $tn = 3;

                $n += 3;

                $noc += 2;

            } elseif (240 <= $t && $t <= 247) {

                $tn = 4;

                $n += 4;

                $noc += 2;

            } elseif (248 <= $t && $t <= 251) {

                $tn = 5;

                $n += 5;

                $noc += 2;

            } elseif ($t == 252 || $t == 253) {

                $tn = 6;

                $n += 6;

                $noc += 2;

            } else {

                $n++;

            }

            if ($noc >= $length) {

                break;

            }

        }

        if ($noc > $length) {

            $n -= $tn;

        }

        $strcut = substr($string, 0, $n);

        $strcut = str_replace(

            array(

                '∵',

                '&',

                '"',

                "'",

                '"',

                '"',

                '—',

                '<',

                '>',

                '·',

                '…'

            ),

            array(

                ' ',

                '&amp;',

                '&quot;',

                '&#039;',

                '&ldquo;',

                '&rdquo;',

                '&mdash;',

                '&lt;',

                '&gt;',

                '&middot;',

                '&hellip;'

            ), $strcut);

    } else {

        $dotlen = strlen($dot);

        $maxi = $length - $dotlen - 1;

        $current_str = '';

        $search_arr = array(

            '&',

            ' ',

            '"',

            "'",

            '"',

            '"',

            '—',

            '<',

            '>',

            '·',

            '…',

            '∵'

        );

        $replace_arr = array(

            '&amp;',

            '&nbsp;',

            '&quot;',

            '&#039;',

            '&ldquo;',

            '&rdquo;',

            '&mdash;',

            '&lt;',

            '&gt;',

            '&middot;',

            '&hellip;',

            ' '

        );

        $search_flip = array_flip($search_arr);

        for ($i = 0; $i < $maxi; $i++) {

            $current_str = ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];

            if (in_array($current_str, $search_arr)) {

                $key = $search_flip[$current_str];

                $current_str = str_replace($search_arr[$key],

                    $replace_arr[$key], $current_str);

            }

            $strcut .= $current_str;

        }

    }

    return $strcut . $dot;

}


/**
 * 显示信息
 *
 * @param string $message
 *            内容
 * @param string $url_forward
 *            跳转的网址
 * @param string $title
 *            标题
 * @param int $second
 *            停留的时间
 * @return
 *
 *
 *

 */

function show_msg($message, $url_forward = '', $title = '提示信息', $second = 3)
{
    include(APPPATH . 'views/admin/show_msg.php');
    exit();
}


/**
 * 图片上传函数
 *
 * @param
 *            string 上传文本框的名称
 * @return string 图片保存在数据库里的路径
 */

function uploadFile($filename, $dir_name = 'image')
{
    // 有上传文件时
    if (empty($_FILES)) {
        return '0'; // 没有上传文件
    }

    $save_path = 'uploads/' . $dir_name . '/';
    $max_size = 5 * 1000 * 1024; // 最大文件大小5M
    $AllowedExtensions = array('jpg', 'jpeg', 'png', 'bmp', 'gif'); // 允许格式
    $file_size = $_FILES[$filename]['size'];
    if ($file_size > $max_size) {
        return '1'; //文件太大了
    }
    $Extensions = fileext($_FILES[$filename]['name']);
    if (!in_array($Extensions, $AllowedExtensions)) {
        return '2'; //请上传指定格式文件
    }

    if (!file_exists($save_path)) { // 创建文件夹
        mkdir($save_path);
    }
    $save_path .= date("Ymd") . "/";
    if (!file_exists($save_path)) {
        mkdir($save_path);
    }
    $file_name = date('His') . '_' . rand(10000, 99999) . '.' . $Extensions;
    $upload_file = $save_path . $file_name;
    if (move_uploaded_file($_FILES[$filename]['tmp_name'], $upload_file) === false) {
        return '3'; //文件上传失败
    }

    return $upload_file;
}


/**
 * 生成缩略图函数
 *
 * @param $imgurl 图片路径
 * @param $width 缩略图宽度
 * @param $height 缩略图高度
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
 */

function mythumb($imgurl, $width = 200, $height = 200)

{

    $fileext = fileext($imgurl);

    $num = strlen($imgurl) - strlen($fileext) - 1;

    $newimg = substr($imgurl, 0, $num) . "_{$width}_{$height}.{$fileext}";


    if (file_exists($newimg))

        return $newimg; // 有，返回


    if (file_exists($imgurl)) { // 没有，开始生成

        include_once APPPATH . '/libraries/My_image_class.php';

        $object = new My_image_class();

        $px = getimagesize($imgurl);

        if ($px[0] > 10) {

            $object->imageCustomSizes($imgurl, $newimg, $width, $height);

            return $newimg;

        }

    }

}


/**
 * 生成缩略图  剪切缩小
 *
 * @param $imgurl 图片路径
 * @param $width 缩略图宽度
 * @param $height 缩略图高度
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
 */

function thumb($imgurl, $width = 200, $height = 150)

{

    if (empty($imgurl))

        return '不能为空';


    include_once('application/libraries/image_moo.php');

    $moo = new Image_moo();

    $moo->load($imgurl);

    $moo->resize_crop($width, $height);

    $moo->save_pa("", "_small");

}


/**
 * 生成两张图片,保留原图
 * small 剪切固定长宽 160*120 , big 等比缩小 800*800
 *
 * @param $imgurl 图片路径
 * @return void
 */

function thumb2($imgurl)

{

    if (empty($imgurl))

        return '不能为空';


    include_once 'application/libraries/image_moo.php';

    $moo = new Image_moo();

    $moo->load($imgurl);

    $moo->resize_crop(200, 200);

    $moo->save_pa("", "_small");

    $moo->resize(800, 800);

    $moo->save_pa("", "_big");


    //if ($moo->width > 800) {

    //}

    // if ($moo->errors) print $moo->display_errors(); 显示错误

}


/**
 * 生成缩略图函数  等比缩小
 *
 * @param $imgurl 图片路径
 * @param $width 缩略图宽度
 * @param $height 缩略图高度
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_100.jpg
 */

function thumb_resize($imgurl, $width = 100, $height = 100)

{

    if (empty($imgurl))

        return 'false';


    include_once('application/libraries/image_moo.php');

    $moo = new Image_moo();

    $moo->load($imgurl);

    $moo->resize($width, $height);

    $moo->save_pa("", "_{$width}_{$height}");

}


/**
 * 生成缩略图函数  等比缩小
 *
 * @param $imgurl 图片路径
 * @param $width 缩略图宽度
 * @param $height 缩略图高度
 * @return string 生成图片的路径 类似：./uploads/201203/img_100_100.jpg
 */

function img_water($imgurl)

{

    if (empty($imgurl))

        return 'false';


    include_once('application/libraries/image_moo.php');

    $moo = new Image_moo();

    $moo->load($imgurl)
        ->load_watermark("watermark.png")
        ->watermark(3)
        ->save_pa("", "_water");

}


/**
 * 取得文件扩展 不包括 点
 *
 * @param $filename 文件名
 * @return 扩展名
 */

function fileext($filename)

{

    // 获得文件扩展名

    $temp_arr = explode(".", $filename);

    $file_ext = array_pop($temp_arr);

    $file_ext = trim($file_ext);

    $file_ext = strtolower($file_ext);


    return $file_ext;

}


/**
 * 在后缀前面加上字符串，返回新名称 uploads/201203/img_100_80.jpg
 *
 * @param $filename 文件名
 * @param $append 附加字符串
 * @return string 新名称
 */

function new_filename($filename, $append = '_small')

{


    if (empty($filename)) return '';


    $fileext = fileext($filename);

    $num = strlen($filename) - strlen($fileext) - 1;

    $new_filename = substr($filename, 0, $num) . $append . '.' . $fileext;

    //if (!file_exists($newimg)) $newimg = $filename;

    return $new_filename;

}


/**
 * 获取请求ip
 *
 * @return ip地址
 */

function ip()

{

    if (getenv('HTTP_CLIENT_IP') &&

        strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')

    ) {

        $ip = getenv('HTTP_CLIENT_IP');

    } elseif (getenv('HTTP_X_FORWARDED_FOR') &&

        strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')

    ) {

        $ip = getenv('HTTP_X_FORWARDED_FOR');

    } elseif (getenv('REMOTE_ADDR') &&

        strcasecmp(getenv('REMOTE_ADDR'), 'unknown')

    ) {

        $ip = getenv('REMOTE_ADDR');

    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] &&

        strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')

    ) {

        $ip = $_SERVER['REMOTE_ADDR'];

    }

    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';

}


/**
 * 写入缓存
 * $name 文件名
 * $data 数据数组
 *
 * @return ip地址
 */

function set_cache($name, $data)

{


    // 检查目录写权限

    if (@is_writable(APPPATH . 'cache/') === false) {

        return false;

    }

    file_put_contents(APPPATH . 'cache/' . $name . '.php',

        '<?php return ' . var_export($data, TRUE) . ';');

    return true;

}


/**
 * 获取缓存
 * $name 文件名
 *
 * @return array
 */

function get_cache($name)

{

    $ret = array();

    $filename = APPPATH . 'cache/' . $name . '.php';

    if (file_exists($filename)) {

        $ret = include $filename;

    }


    return $ret;

}


/**
 * 对数据执行 trim 去左右两边空格
 * mixed $data 数组或者字符串
 *
 * @return mixed
 */

function trims($data)

{

    if (is_array($data)) {

        foreach ($data as &$r) {

            $r = trims($r);

        }

    } else {

        $data = trim($data);

    }


    return $data;

}


/**
 * 时间处理
 */

function times($time, $type = 0)

{

    if ($type == 0) {

        return date('Y-m-d', $time);

    } else {

        return date('Y-m-d H:i:s', $time);

    }

}


/**
 * 获取分类 指定id 的信息
 */

function category($catid, $type = 'name')

{

    $a = get_cache('category');

    return $a[$catid][$type];

}


/**
 * 获取分类 指定id 的信息
 */

function getcitys($catid, $type = 'name')

{

    $a = get_cache('citys');

    return $a[$catid][$type];

}


/**
 * 后去加密后的 字符
 *
 * @param
 *            string
 * @return string
 */

function get_password($password)
{
    return md5('ddgfdgd5454_' . $password);
}


/**
 * 取消反引用 返回经stripslashes处理过的字符串或数组
 *
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */

function new_stripslashes($string)
{
    if (!is_array($string))

        return stripslashes($string);

    foreach ($string as $key => $val)

        $string[$key] = new_stripslashes($val);

    return $string;

}


/**
 * 将字符串转换为数组
 *
 * @param string $data
 * @return array
 *

 */

function string2array($data)

{

    if ($data == '')

        return array();

    @eval("\$array = $data;");

    return $array;

}


/**
 * 将数组转换为字符串
 *
 * @param array $data
 * @param bool $isformdata
 * @return string
 *

 */

function array2string($data, $isformdata = 1)

{

    if ($data == '')

        return '';

    if ($isformdata)

        $data = new_stripslashes($data);

    return (var_export($data, TRUE)); // addslashes

}


/**
 * 得到子级 id 包括自己
 *
 * @param
 *            int
 * @return string
 *

 */

function get_child($myid)

{

    $ret = $myid;

    $data = get_cache('category');

    foreach ($data as $id => $a) {

        if ($a['parentid'] == $myid) {

            $ret .= ',' . $id;

        }

    }


    return $ret;

}


/**
 * 得到子级 id 包括自己
 *
 * @param
 *            int
 * @return array
 *

 */

function get_childarray($myid)

{

    $return = array();

    $data = get_cache('category');

    foreach ($data as $id => $a) {

        if ($a['parentid'] == $myid) {

            $return[$id] = $a;

        }

    }


    return $return;

}


// 获取限制条件 返回数组

function getwheres($intkeys, $strkeys, $randkeys, $likekeys, $pre = '')

{

    $wherearr = array();

    $urls = array();


    foreach ($intkeys as $var) {

        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';

        if (strlen($value)) {

            $wherearr[] = "{$pre}{$var}='" . intval($value) . "'";

            $urls[] = "$var=$value";

        }

    }


    foreach ($strkeys as $var) {

        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';

        if (strlen($value)) {

            $wherearr[] = "{$pre}{$var}='$value'";

            $urls[] = "$var=" . rawurlencode($value);

        }

    }


    foreach ($randkeys as $vars) {

        $value1 = isset($_GET[$vars[1] . '1']) ? $vars[0]($_GET[$vars[1] . '1']) : '';

        $value2 = isset($_GET[$vars[1] . '2']) ? $vars[0]($_GET[$vars[1] . '2']) : '';

        if ($value1) {

            $wherearr[] = "{$pre}{$vars[1]}>='$value1'";

            $urls[] = "{$vars[1]}1=" . rawurlencode($_GET[$vars[1] . '1']);

        }

        if ($value2) {

            $wherearr[] = "{$pre}{$vars[1]}<='$value2'";

            $urls[] = "{$vars[1]}2=" . rawurlencode($_GET[$vars[1] . '2']);

        }

    }


    foreach ($likekeys as $var) {

        $value = isset($_GET[$var]) ? stripsearchkey($_GET[$var]) : '';

        if (strlen($value) > 1) {

            $wherearr[] = "{$pre}{$var} LIKE BINARY '%$value%'";

            $urls[] = "$var=" . rawurlencode($value);

        }

    }


    return array(

        'wherearr' => $wherearr,

        'urls' => $urls

    );

}


// 获取下拉框 选项信息

function getSelect($data, $value = '', $type = 'key')

{

    $str = '';

    foreach ($data as $k => $v) {

        if ($type == 'key') {

            $seled = ($value == $k && $value) ? 'selected="selected"' : '';

            $str .= "<option value=\"{$k}\" {$seled}>{$v}</option>";

        } else {

            $seled = ($value == $v && $value) ? 'selected="selected"' : '';

            $str .= "<option value=\"{$v}\" {$seled}>{$v}</option>";

        }

    }

    return $str;

}


// 显示友好的时间格式

function timeFromNow($dateline)

{

    if (empty($dateline)) return false;

    $seconds = time() - $dateline;

    if ($seconds < 60) {

        return "1分钟前";

    } elseif ($seconds < 3600) {

        return floor($seconds / 60) . "分钟前";

    } elseif ($seconds < 24 * 3600) {

        return floor($seconds / 3600) . "小时前";

    } elseif ($seconds < 48 * 3600) {

        return date("昨天 H:i", $dateline) . "";

    } else {

        return date('m-d', $dateline);

    }

}

/**
 * 格式化 时间处理
 */
function t_time($time = 0, $type = 1)
{
    if ($time == 0) $time = time();
    if ($type == 0) {
        return date('Y-m-d', $time);  //0
    } else if ($type == 1) {
        return date('Y-m-d H:i:s', $time); //1
    } else if ($type == 2) {
        return date('Y年m月d日', $time); //2
    }
}

/**
 *  计算两个日期间隔的天数
 */
function diffBetweenTwoDays ($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);
    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    return ceil(($second1 - $second2)/86400);
}

// 统一格式，输出 json
function t_json($data = array(), $code = 0, $msg = 'ok')
{
    $result = array(
        'code' => strval($code),
        'msg' => $msg,
        'time' => t_time(),
        'data' => $data,
    );
    if (isset($_GET['dump'])) {
        dump($result);
        return;
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

// 统一格式，输出 json
function t_error($code = 1, $msg = 'error message', $data = array())
{
    return t_json($data, $code, $msg);
}


//检查邮箱是否有效

function isemail($email)

{

    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);

}


//根据IP定位 获取城市

function getCityCode()

{

    $temp = json_decode(file_get_contents('http://api.map.baidu.com/location/ip?ak=EFb35215c1d4b7b98a89a896ac91c025&coor=bd09ll'));


    return $temp->content->address_detail->city_code;

}


// 异步执行

// async_request('192.168.1.223','/web/test2.php?a=onNzZjt7c1wLTcdpp-HBnaLQKbwI');

function async_request($host, $file, $method = 'get')
{


    $fp = fsockopen($host, 80, $errno, $errstr, 30);

    if (!$fp) {

        echo "$errstr ($errno)<br />\n";

    } else {

        $out = "GET $file / HTTP/1.1\r\n";

        $out .= "Host: www.example.com\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        /*忽略执行结果
         while (!feof($fp)) {
        echo fgets($fp, 128);
        }*/
        fclose($fp);
    }
}


function dump($var, $vardump = false)
{
    print "<pre>";
    ($vardump) ? (print_r($var)) : (var_dump($var));
    print "</pre>";
}

/**
 * curl request
 *
 * @param string $url
 * @param array $get ['key'=>'value','key2'=>'value2']
 * @param array $post : ['key'=>'value','key2'=>'value2']
 * @param array $header : ['key'=>'value','key2'=>'value2']
 * @return array
 */
function t_curl($url, $get = array(), $post = array(), $header = array(), $times = 1)
{
    if ($get) {
        $url .= '?';
        $url .= http_build_query($get, '&');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, true);

    // 设置User-Agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'abc');
    // 连接建立最长耗时
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    // 请求最长耗时
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 如果报证书相关失败,可以考虑取消注释掉该行,强制指定证书版本

    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        $post = http_build_query($post, '&');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    if ($header) {
        $header_true = array();
        foreach ($header as $key => $value) {
            $header_true[] = "{$key}:{$value}";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_true);
    }

    // 执行请求
    $output = curl_exec($ch);
    // 解析Response
    $response = array();
    $errorCode = curl_errno($ch);
    if ($errorCode) {
        if ($errorCode === 28) {
            throw new APIConnectionException("Response timeout. Your request has probably be received by JPush Server,please check that whether need to be pushed again.", true);
        } else if ($errorCode === 56) {
            // resolve error[56 Problem (2) in the Chunked-Encoded data]
            throw new APIConnectionException("Response timeout, maybe cause by old CURL version. Your request has probably be received by JPush Server, please check that whether need to be pushed again.", true);
        } else if ($times >= 3) {
            throw new APIConnectionException("Connect timeout. Please retry later. Error:" . $errorCode . " " . curl_error($ch));
        } else {
            t_curl($url, $get, $post, $header, ++$times);
        }
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($output, 0, $header_size);
        $body = substr($output, $header_size);
        $headers = array();
        foreach (explode("\r\n", $header_text) as $i => $line) {
            if (!empty($line)) {
                if ($i === 0) {
                    $headers['http_code'] = $line;
                } else if (strpos($line, ": ")) {
                    list ($key, $value) = explode(': ', $line);
                    $headers[$key] = $value;
                }
            }
        }
        $response['headers'] = $headers;
        $response['body'] = $body;
        $response['http_code'] = $httpCode;
    }
    curl_close($ch);
    return $response;
}

/**
 * 获取一个随机字符串MD5格式
 *
 * @param
 *
 * @return string
 */
function t_rand_str($str='')
{
    return md5($str.microtime() . 'fdsfsdfs567' . rand());
}

/**
 * 计算加速需要的闪电
 *
 * @param  剩余时间
 *
 * @return number 需要的闪电
 */
function count_shandian($time){
    $number = 0;
    switch ($time)
    {
        case $time>0 && $time<=300 : $number = ceil($time/30);
            break;
        case $time>300 && $time<=600 : $number = 10+ceil(($time-300)/60);
            break;
        case $time>600 && $time<=1200 : $number = 15+ceil(($time-600)/90);
            break;
        case $time>1200 && $time<=3600 : $number = 22+ceil(($time-1200)/120);
            break;
        case $time>3600 && $time<=7200 : $number = 42+ceil(($time-3600)/150);
            break;
        case $time>7200  : $number = 66+ceil(($time-7200)/180);
            break;
    }

    return $number;
}

function get_captcha(){
    //设置验证码内容
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < 5; $i++)
    {
        $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
    }
    $word = $str;
    $_SESSION['captcha']=$word;
    $vals = array(

        'word' => $word,
        'img_path' => dirname(BASEPATH).'/uploads/captcha/',
        'img_url' => base_url('uploads/captcha').'/',
        'img_width' => 100,
        'img_height' => 40,
        'expiration' =>30,
        'word_length'   => 8,
        'font_size' => 40,
        'colors'    => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            'grid' => array(255, 40, 40)
        )
    );
    $cap = create_captcha($vals);
    $parrent = "/(href|src)=([\"|']?)([^\"'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i";
    $str = $cap['image'];
    preg_match($parrent,$str,$match);

    return $match[3];


}

/**
 * http get
 * @param string $url
 * @return http response body
 */
function httpGet($url)
{

    $ch = curl_init();

    __setSSLOpts($ch, $url);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    return __exec($ch);
}

/**
 * http post
 * @param string $url
 * @param string or dict $postData
 * @return http response body
 */
function httpPost($url, $postData)
{
    $ch = curl_init();

    __setSSLOpts($ch, $url);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    return __exec($ch);
}

//
// private:
//

function __setSSLOpts($ch, $url)
{
    if (stripos($url,"https://") !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
    }
}

function __exec($ch)
{
    $output = curl_exec($ch);
    $status = curl_getinfo($ch);
    curl_close($ch);

    return $output;
}


/**
 * 验证输入的手机号码是否合法
 * @access  public
 * @param   string    $mobile_phone     需要验证的手机号码
 * @return bool
 */
function is_mobile_phone($mobile_phone)
{

    $chars = "/^(1(([345789][0-9])|(47)))\d{8}$/";
    if(preg_match($chars, $mobile_phone))
    {
        return $mobile_phone;
    }
    else
    {
       return false;
    }


}





function topMenus()
{
    $ci = &get_instance();
    $ci->load->model('admin/menu_model','menu_model');
    $list = $ci->menu_model->topMenu();

    $str = '';
    foreach($list as $value)
    {

        $show =  $_SESSION['nav'] == $value['nav'] ? "layui-this":'';

        $str .= "<li class='layui-nav-item $show'>";
        $str .= "<a   href=". site_url($value['type'].'/'.$value['url']) .">".$value['name']."</a>";
        $str .= "</li>";

    }

    return $str;


}

function leftMenus()
{
    $ci = &get_instance();
    $ci->load->model('admin/menu_model','menu_model');
    $list = $ci->menu_model->leftMenu();
    $str = '';
    foreach($list as $value)
    {

        if($_SESSION['nav'] == $value['nav'])
         {
             $str .= "<ul class='layui-nav layui-nav-tree site-demo-nav'>";
             $str .= "<li class='layui-nav-item layui-nav-itemed'>";
             if($value['title'])
             {
                 $str .= "<a class='javascript:;'  href='javascript:;'>".$value['name']."</a>";
                 if(count($value['child'])>0)
                 {
                    foreach($value['child'] as $val)
                    {
                        $string = explode("/",$val['url']);
                        $contro = $string[0];
                        $funct =$string[1];

                        $type  = explode("?",$funct)[1]?explode("?",$funct)[1]:'';
                        $funct = explode("?",$funct)[1]?explode("?",$funct)[0]:$funct ;

                        $str .= "<dl class='layui-nav-child'>";
                        $permi = permission($val['priv_sign'],'read');

                        $str .= "<dd class=".side_show($contro,$funct,$type) . " '$permi' ?' show':' layui-hide'; >";
                        $str .= " <a href=". site_url($val['type'].'/'.$val['url']) .">".$val['name']."</a>";
                        $str .= " </dd>";
                        $str .= "</dl>";
                    }
                 }
             }

             $str .= "</li>";
             $str .= "</ul>";
          }




    }

    return $str;


}

/**
 * 检测是否为excel文件程序
 * excel文件导入
 *string $file需要导入的文件
 */
function detectUploadFileMIME($file) {
    // 1.through the file extension judgement 03 or 07
    $flag = 0;
    $file_array = explode ( ".", $file ["name"] );
    $file_extension = strtolower ( array_pop ( $file_array ) );

    // 2.through the binary content to detect the file
    switch ($file_extension) {
        case "xls" :
            // 2003 excel
            $fh = fopen ( $file ["tmp_name"], "rb" );
            $bin = fread ( $fh, 8 );
            fclose ( $fh );
            $strinfo = @unpack ( "C8chars", $bin );
            $typecode = "";
            foreach ( $strinfo as $num ) {
                $typecode .= dechex ( $num );
            }
            if ($typecode == "d0cf11e0a1b11ae1") {
                $flag = 1;
            }
            break;
        case "xlsx" :
            // 2007 excel
            $fh = fopen ( $file ["tmp_name"], "rb" );
            $bin = fread ( $fh, 4 );
            fclose ( $fh );
            $strinfo = @unpack ( "C4chars", $bin );
            $typecode = "";
            foreach ( $strinfo as $num ) {
                $typecode .= dechex ( $num );
            }
            // echo $typecode;
            if ($typecode == "504b34") {
                $flag = 1;
            }
            break;
    }

    // 3.return the flag
    return $flag;
}

// 导入Excel
function excel($sheet='',$row='',$column='')
{
    $CI = &get_instance();
    if ($_FILES['file']['error']) {
        switch ($_FILES['file']['error']) {
            case 1:
                show_msg('文件大小超出了服务器的空间大小');
                die;
                break;
            case 2:
                show_msg('要上传的文件大小超出浏览器限制');
                die;
                break;
            case 3:
                show_msg('文件仅部分被上传');
                die;
                break;
            case 4:
                show_msg('没有找到要上传的文件');
                die;
                break;
            case 5:
                show_msg('服务器临时问价丢失');
                die;
                break;
            case 6:
                show_msg('文件写入到临时文件夹出错');
                die;
                break;
            default:
                show_msg('文件上传未知错误');
        }
        die;
    }

    //接收的excel信息
    $info = pathinfo($_FILES['file']['name']);

    $file = $_FILES['file'];

    //验证excel文件类型
    $res = detectUploadFileMIME($file);
    if ($res) {
        $tmp_name = $info['filename'];
        $new_name = $tmp_name . '-' . time() . '.' . $info['extension'];
        $root_path = dirname(BASEPATH);

        $new_dir = $root_path . '/data/excel/';
        if (!file_exists($new_dir)) {
            mkdir($new_dir, 0777, true);
        }
        $new_path = $new_dir . $new_name;
        $new_path = str_replace('\\', DIRECTORY_SEPARATOR, $new_path);
        $new_path = str_replace('/', DIRECTORY_SEPARATOR, $new_path);
        $new_path = iconv("UTF-8", "GBK//IGNORE", $new_path);
        $path = $_FILES['file']['tmp_name'];

        $res = move_uploaded_file($path, $new_path);

        if (!$res) {
            show_msg('上传失败');
            die;
        };

        if (!file_exists($new_path)) {
            exit("文件" . $new_path . "不存在");
        }
        /*加载PHPExcel*/
        $CI->load->library('PHPExcel.php');
        $CI->load->library('PHPExcel/IOFactory.php');
        // $this->load->library('PHPExcel/Reader/Excel5.php');
        $objPHPExcel = new PHPExcel();

        $objProps = $objPHPExcel->getProperties();

        //设置excel格式
        if ($info['extension'] == 'xlsx') {
            $objReader = IOFactory::createReader('Excel2007');
        } else {
            $objReader = IOFactory::createReader('Excel5');
        }

        //载入excel文件
        $objPHPExcel = $objReader->load($new_path);
        //读取第一张表
        $sheet = $objPHPExcel->getSheet($sheet);

        //获取总行数
        $highestRow = $sheet->getHighestRow($row);
        //获取总列数
        $highestColumn = $sheet->getHighestColumn($column);

        $result['sheet'] = $sheet;
        $result['highestRow'] = $highestRow;
        $result['highestColumn'] = $highestColumn;
        $result['objPHPExcel'] = $objPHPExcel;
        return $result;

    }

}

if(!function_exists('isUrl'))
{
    function isUrl($url)
    {
        $url = preg_replace('/([\x80-\xff]*)/i','',$url);
        $search = "/^(http|https|ftp):\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\”])*$/";
        $result = preg_match($search, trim($url));

        return $result;
    }

}
