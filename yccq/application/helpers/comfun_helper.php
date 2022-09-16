<?php
/**
 * User: Administrator
 * Date: 2020/12/10
 * Time: 16:22
 */



function load_view($view='', $vars = '', $return = FALSE)
{

    $CI =&get_instance();
   return $CI->load->view('admin/'.$view, $vars, $return);

}

/**
 * 过滤词库
 * Date: 2020/1/17
 * Time: 10:10
 * $string 要过滤的内容
 */

function getMain($string)
{

    $ci = &get_instance();
    $ci->load->model('filteredtext/filterwords_model','filterwords_model');
    $result = $ci->filterwords_model->getMain($string);
    $result = filter_keyword($result);
    return $result;

}

/**
 * 获取ip
 * Date: 2020/12/10
 * Time: 10:10
 * $string 要过滤的内容
 */
function get_real_ip()
{
    if (getenv('HTTP_CLIENT_IP'))
    {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    if (getenv('HTTP_X_REAL_IP'))
    {
        $ip = getenv('HTTP_X_REAL_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR'))
    {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip = $ips[0];
    }
    elseif (getenv('REMOTE_ADDR'))
    {
        $ip = getenv('REMOTE_ADDR');
    }
    else
    {
        $ip = '0.0.0.0';
    }

    return $ip;
}

/** 用户代理
 *
*/
function get_agent()
{
    $ci = &get_instance();
    $ci->load->library('user_agent');
    $user_agent = $ci->agent->platform() . '/' . $ci->agent->browser() . $ci->agent->version();
    return $user_agent;
}


/**
*把用户输入的文本转义（主要针对特殊符号和emoji表情）
 */
function userTextEncode($str){
    if(!is_string($str))return $str;
    if(!$str || $str=='undefined')return '';

    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
        return addslashes($str[0]);
    },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，很多emoji实际上是\ud开头的，暂时没发现有\ue开头。
    return json_decode($text);
}

/**
*解码上面的转义
 */
function userTextDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}

/**
*去除emoji表情
 * $clean_text 返回空
 */
function removeEmoji($text) {
    $text=trim($text);
    $clean_text = "";
// Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);
// Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);
// Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);
// Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);
// Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    return $clean_text;
}


/**
 * 根据ip获取省份
 */

function GetIpLookup($ip){

    if(!empty($ip)){
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){ return false; }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        return $json;
    }


}


 function get_rand_num($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur)
    {

        $randNum = mt_rand(0, $proSum);
        if($randNum <= $proCur)
        {
            $result = $key;
            break;
        }
        else
        {
            $proSum -= $proCur;
        }
    }

    unset ($proArr);
    return $result;
}

/**
 * 接收base64的图片转为正常的并保存
 */
 function base64image($img=''){
    $image="data:image/jpeg;base64,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";
    $base64_image_content=$image;
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $path =$_SERVER['DOCUMENT_ROOT']."/yccq/base64img/coolrun/";//创建路径
        if (!is_dir($path)) {
            @mkdir('.' . $path, 0777, true);
        }
        $new_file = $path.date('Ymd',time())."/";
        if(!file_exists($new_file)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777,true);
        }
        $new_file = $new_file.time().".{$type}";
//                dump(str_replace($_SERVER['DOCUMENT_ROOT'],'', $new_file));die;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
//                 dump($new_file);die;
            return $new_file;
        }else{
            return false;
        }
    }else{
        return false;
    }
}