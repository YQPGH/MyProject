<?php

/**
 * 本项目特有的函数，结合CI用的函数
 * by tangjian
 */


// 返回模型 API下
function model($model_name)
{
    $CI = &get_instance();
    $CI->load->model('api/' . $model_name);
    return $CI->{$model_name};
}


// 获取配置元素 ，配置必须是数组的
function config($config, $key)
{
    $CI = &get_instance();
    $array = $CI->config->item($config);
    return $array[$key];
}

// 去掉html符号， 除指定字段外
function html_escape_move($data, $move_arr = array())
{
    if ($move_arr) {
        foreach ($data as $key => &$value) {
            if (!in_array($key, $move_arr)) {
                $value = html_escape($value);
            }
        }
    }

    return $data;
}

// 获取配置元素 ，配置必须是数组的
function side_show($controller_name, $mothed = '', $getstr = '')
{
    $CI = &get_instance();

    if ($mothed) {
        if ($CI->uri->segment(2) == $controller_name && $CI->uri->segment(3) == $mothed) {

            if ($getstr) {
                $temp = explode('=', $getstr);

                if ($_GET[$temp[0]] == $temp[1]) {
                    return 'layui-this';
                } else {
                    return '';
                }
            }

            return 'layui-this';
        } else {
            return '';
        }
    } else {
        return $CI->uri->segment(2) == $controller_name ? 'layui-this' : '';
    }
}


/**
 * 查询是否有权限
 * @param string 权限标识
 * @param string 权限类型 read、write、del
 * echo string
 */
function permission($priv_sign,$type)
{
    $ci = &get_instance();
    $res = false;
    // 超级管理 显示
    $admin = $ci->session->userdata('admin');

//    var_dump($admin) ;exit;

    if($admin['groupid'] == 1) return true;
    
    // 查看是否有权限
    $ci->load->model('admin/admin_group_model','group_model');
    $ci->load->model('admin/admin_priv_model','priv_model');
    $result = $ci->group_model->row($admin['groupid']);

    $priv = $ci->priv_model->row(array('priv_sign'=>$priv_sign));

    if(!empty($priv)){
        switch ($type) {
            case 'read':
                $priv_read = explode(',', $result['priv_read']);

                if(in_array($priv['id'], $priv_read)){
                    $res = true;
                }
                break;
            case 'write':
                $priv_write = explode(',', $result['priv_write']);
                if(in_array($priv['id'], $priv_write)){
                    $res = true;
                }
                break;
            case 'del':
                $priv_del = explode(',', $result['priv_del']);
                if(in_array($priv['id'], $priv_del)){
                    $res = true;
                }
                break;
        }
    }

    return $res;
}


// 获取广告信息

function ad($id)
{
    $result = '';
    $CI = &get_instance();
    $CI->load->model('ad_model');
    $value = $CI->ad_model->row(array('id' => $id));
    if ($value) {
    }

    return $result;
}


// 获取列表
function getCategoryChild($catid = 0)
{
    $CI = &get_instance();
    $CI->load->model('category_model');
    $list = $CI->category_model->get_child($catid);
    return $list;
}


// 获取列表

function getCategoryName($catid)
{
    $CI = &get_instance();
    $CI->load->model('category_model');
    $list = $CI->category_model->get_name($catid);
    return $list;
}

/**
 * 过滤sql与php文件操作的关键字
 * @param string $string
 * @return string
 */
function filter_keyword( $string ) {
    $keyword = 'select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|and|union|order|or|into|load_file|outfile|removed|alert';
    $arr = explode( '|', $keyword );
    $result = str_ireplace( $arr, '', $string );
    return $result;
}

/**
 * 检查输入的数字是否合法，合法返回对应id，否则返回false
 * @param integer $id
 * @return mixed
 */
function check_id( $id ) {
    $result = false;
    if ( $id !== '' && !is_null( $id ) ) {
        $var = filter_keyword( $id ); // 过滤sql与php文件操作的关键字
        if ( $var !== '' && !is_null( $var ) && is_numeric( $var ) ) {
            $result = intval( $var );
        }
    }
    if($id == $result){

        return $result;
    }else{
        show_msg("非法操作！");exit;
    }

}

/**
 * 检查输入的字符是否合法，合法返回对应字符串，否则返回false
 * @param string $string
 * @return mixed
 */
function check_str( $string ) {
    $var = filter_keyword( $string ); // 过滤sql与php文件操作的关键字
    if ( !empty( $var ) ) {
        if ( !get_magic_quotes_gpc() ) { // 判断magic_quotes_gpc是否为打开
            $var = addslashes( $var ); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
        }
        $var = str_replace( "_", "\_", $var ); // 把 '_'过滤掉
        $var = str_replace( "%", "\%", $var ); // 把 '%'过滤掉
        $var = nl2br( $var ); // 回车转换
        $var = htmlspecialchars( $var ); // html标记转换
    }
    if($string === $var){
        return $var;
    }else{
        show_msg("非法操作！");exit;
    }

}

/**
 * 模拟POST提交数据
 * @param string $url 链接地址
 * @param array $data 数组
 */
 function https_request($url,$data = null){

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output,true);
}