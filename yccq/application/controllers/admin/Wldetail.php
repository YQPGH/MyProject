<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Wldetail extends Content{

    function __construct(){
        $this->name = '物料明细';
        $this->control = 'wldetail';
        $this->poke_view = 'admin/wl_poke_list'; //扑克牌列表页
        $this->notebook_view = 'admin/wl_notebook_list';
        $this->mouse_pad_view = 'admin/wl_mouse_pad_list';
        $this->pillow_view = 'admin/wl_pillow_list';
        $this->ashtray_view = 'admin/wl_ashtray_list';
        $this->heater_view = 'admin/wl_heater_list';
        $this->towel_view = 'admin/wl_towel_list';
        $this->wash_bag_view = 'admin/wl_wash_bag_list';

        parent ::__construct();
        $_SESSION['nav'] = 8;
        $this->baseurl = site_url('admin/wldetail/');
        $this->load->model('admin/wldetail_model','model');

    }

    // 导入数据
    public function excel_dialog()
    {

//        $url = site_url('admin/wldetail/excelIn');
//
//        $data['url'] = $url;
//        $this->load->view('admin/excel', $data);
    }

    // 导入Excel
    function excelIn()
    {


        $result = excel(3);  //表，行数，列数
        $this->db->trans_start();
//print_r($result);exit;
        if ($result)
        {
            for ($currentRow = 1; $currentRow <= $result['highestRow']; $currentRow++) {

//                $insert_id = $this->db->insert('wl_record',[
//                    'prize'=>2,
//                    'title' => 'sd',
////                    'openid' => $result['objPHPExcel']->getActiveSheet()->getCell("A" . $currentRow)->getValue(),
//                    'truename' => $result['objPHPExcel']->getActiveSheet()->getCell("A" . $currentRow)->getValue(),
////                    'nickname' => $result['objPHPExcel']->getActiveSheet()->getCell("B" . $currentRow)->getValue(),
//                    'phone' =>$result['objPHPExcel']->getActiveSheet()->getCell("B" . $currentRow)->getValue(),
//                    'address' => $result['objPHPExcel']->getActiveSheet()->getCell("C" . $currentRow)->getValue(),
//                    'time' => '',
//                    'num' => $result['objPHPExcel']->getActiveSheet()->getCell("D" . $currentRow)->getValue(),
//                    'express'  => '邮政快递',
//                    'odd_numbers' => $result['objPHPExcel']->getActiveSheet()->getCell("E" . $currentRow)->getValue(),
//                    'status' => 1,
//                ]);
            }
        }
        $this->db->trans_complete();

//        if($insert_id) show_msg('导入成功！');

    }



    function poke_list(){

        if(!permission('SYS_Poke_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/poke_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_poke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_poke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询真龙君扑克牌获奖明细',1);

        $this->load->view($this->poke_view, $data);
    }

    function notebook_list(){
        if(!permission('SYS_Notebook_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/notebook_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_notebook",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_notebook','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询真龙君笔记本获奖明细',1);

        $this->load->view($this->notebook_view, $data);
    }

    function mousepad_list(){
        if(!permission('SYS_Mouse_Pad_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/mousepad_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_mouse_pad",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_mouse_pad','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询香草传奇定制加长加宽鼠标垫获奖明细',1);

        $this->load->view($this->mouse_pad_view, $data);
    }

    function pillow_list(){
        if(!permission('SYS_Pillow_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/pillow_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_pillow",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_pillow','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询香草传奇抱枕被获奖明细',1);

        $this->load->view($this->pillow_view, $data);
    }

    function ashtray_list(){
    if(!permission('SYS_Ashtray_List','read')) show_msg('没有操作权限！');
    $_SESSION['nav'] = 8;
    $url_forward = $this->baseurl . '/ashtray_list?';

    // 查询条件
    $where = '1=1 ';
    $catid = $_REQUEST['catid'];
    if ($catid) {
        $data['catid'] = $catid;
        $url_forward .= '&catid=' . $catid;
        $where .= " AND catid='$catid' ";
    }
    $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
    if ($keywords) {
        $data['keywords'] = $keywords;
        $url_forward .= "&keywords=" . rawurlencode($keywords);
        $keywords = $this->db->escape_like_str($keywords);

        $field = $_REQUEST['field'];
        $url_forward .= "&field=" . rawurlencode($field);
        $data['field'] = $field;
        $where .= " AND $field like '%{$keywords}%' ";

    }

    // URL及分页
    $offset = intval($_GET['per_page']);
    $data['count'] = $this->model->table_count("wl_ashtray",$where);
    $data['pages'] = $this->page_html($url_forward, $data['count']);
    $this->url_forward($url_forward . '&per_page=' . $offset);

    // 列表数据

    $list = $this->model->table_lists('wl_ashtray','*', $where, 'time DESC', $this->per_page, $offset);
    $day = 25569;//excel和php之间相差的时间
    $time = 24 * 60 * 60;//一天24小时

    foreach($list as &$value)
    {

        $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
        unset($value['id'],$value['time']);

    }

    $data['list'] = ($list);

    // 搜索
    $data['fields'] = [
        'openid' => 'openID',
        'truename' => '用户姓名',
        'odd_numbers' => '单号'
    ];
    //后台访问日志
    $this->log_admin_model->logs('查询乐豆中心水晶烟灰缸获奖明细',1);

    $this->load->view($this->ashtray_view, $data);
}

    function heater_list(){
        if(!permission('SYS_Heater_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/heater_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_heater",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_heater','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询乐豆中心超静音创意桌面暖风机获奖明细',1);

        $this->load->view($this->heater_view, $data);
    }

    function towel_list(){
        if(!permission('SYS_Towel_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/towel_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_towel",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_towel','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询洁丽雅纯棉舒适面巾获奖明细',1);

        $this->load->view($this->towel_view, $data);
    }

    function washbag_list(){
        if(!permission('SYS_Wash_Bag_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/washbag_list?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_wash_bag",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_wash_bag','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',
            'odd_numbers' => '单号'
        ];
        //后台访问日志
        $this->log_admin_model->logs('查询swissgear洗漱包获奖明细',1);

        $this->load->view($this->wash_bag_view, $data);
    }

    function theme_list_1(){

        if(!permission('SYS_Theme_List_1','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/theme_list_1?';

        // 查询条件
        $where = '1=1 ';
        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:'鸿韵';
        $where .= " and theme=1 and prize= "."'{$type}' ";

        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        $data['tab_list'] = [
            '鸿韵',
            '凌云',
            '起源'
        ];
        $data['tab_type'] = $type;
        //后台访问日志
        $this->log_admin_model->logs('查询“烟草传奇”游戏公测方案真龙样品烟B邮寄订单信息',1);

        $this->load->view('admin/wl_theme1_smoke_list', $data);
    }

    function theme_list_2(){

        if(!permission('SYS_Theme_List_2','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/theme_list_2?';

        // 查询条件
        $where = '1=1 ';
        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:'鸿韵';
        $where .= " and theme=2 and prize= "."'{$type}' ";

        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        $data['tab_list'] = [
            '鸿韵',
            '凌云',
            '起源'
        ];
        $data['tab_type'] = $type;
        //后台访问日志
        $this->log_admin_model->logs('查询《烟草传奇》主题游戏活动方案真龙样品烟B邮寄名单信息',1);

        $this->load->view('admin/wl_theme2_smoke_list', $data);
    }

    function theme_list_3(){

        if(!permission('SYS_Theme_List_3','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/theme_list_3?';

        // 查询条件
        $where = '1=1 ';
        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:'鸿韵';
        $where .= " and theme=3 and prize= "."'{$type}' ";

        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        $data['tab_list'] = [
            '鸿韵',
            '凌云',
            '起源'
        ];
        $data['tab_type'] = $type;
        //后台访问日志
        $this->log_admin_model->logs('查询《烟草传奇》游戏公测活动方案真龙样品烟B邮寄名单信息',1);

        $this->load->view('admin/wl_theme3_smoke_list', $data);
    }

    function theme_list_4(){

        if(!permission('SYS_Theme_List_4','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/theme_list_4?';

        // 查询条件
        $where = '1=1 ';
        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:'鸿韵';
        $where .= " and theme=4 and prize= "."'{$type}' ";

        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        $data['tab_list'] = [
            '鸿韵',
            '凌云',
            '起源'
        ];
        $data['tab_type'] = $type;
        //后台访问日志
        $this->log_admin_model->logs('查询《香草传奇》游戏新春主题营销活动真龙样品烟B邮寄名单信息',1);

        $this->load->view('admin/wl_theme4_smoke_list', $data);
    }

    function nov11_list(){

        if(!permission('SYS_Qixi','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '金叶1+1，快乐双11奖品明细';
        $url_forward = $this->baseurl . '/nov11_list?';

        // 查询条件
        $where = "1=1  and theme='nov_11 '";


        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';
            $value['express'] = $value['express']==''?'邮政快递':$value['express'];

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        //后台访问日志
        $this->log_admin_model->logs('查询《香草传奇》游戏金叶1+1，快乐双11邮寄名单信息',1);

        $this->load->view('admin/wl_nov11_list', $data);
    }

    function midautumn_list()
    {
        if(!permission('SYS_Midautumn','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '月圆情99-与你共婵娟奖品明细';
        $url_forward = $this->baseurl . 'midautumn_list?';

        // 查询条件
        $where = "1=1  and theme='midautumn '";


        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';
            $value['express'] = $value['express']==''?'邮政快递':$value['express'];

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        //后台访问日志
        $this->log_admin_model->logs('查询《香草传奇》游戏月圆情99-与你共婵娟邮寄名单信息',1);

        $this->load->view('admin/wl_midautumn_list', $data);
    }

    function qixi_list()
    {
        if(!permission('SYS_Qixi','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '乘风破浪来见你奖品明细';
        $url_forward = $this->baseurl . '/qixi_list?';

        // 查询条件
        $where = "1=1  and theme='qixi '";


        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_smoke",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_smoke','*', $where, 'time DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时

        foreach($list as &$value)
        {

            $value['add_time'] = gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time);
            $value['status'] = '已发货';
            $value['express'] = $value['express']==''?'邮政快递':$value['express'];

            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];

        //后台访问日志
        $this->log_admin_model->logs('查询《香草传奇》游戏乘风破浪来见你邮寄名单信息',1);

        $this->load->view('admin/wl_qixi_list', $data);
    }

    function getWlconfig($id=0)
    {
        $sql = "select id,title from wl_config ORDER BY id ASC ";
        $list = $this->db->query($sql)->result_array();
        $row = '';
        if($id)
        {
            $row_sql = "select title from wl_config WHERE id=?";
            $row = $this->db->query($row_sql,$id)->row_array();
        }


        $result = [
            'list' => $list,
            'row' => $row
        ];
        return $result;
    }

    function newyearTheme()
    {

        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '《香草传奇》游戏新春主题营销活动邮寄名单';
        $url_forward = $this->baseurl . '/newyearTheme?';

        // 查询条件
        $where = '1=1 ';

        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:1;
        $where .= " and title='xinchun' and prize= "."'{$type}' ";

        $keywords = trim($_REQUEST['keywords']);

        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_record",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_record','*', $where, 'id DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时
        $status = ['未发货','已发货'];
        foreach($list as &$value)
        {

            $value['time'] = $value['time']?gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time):'';
            $value['status'] = $status[$value['status']];
            $res = $this->getWlconfig($value['prize']);
            $value['prize'] = $res['row']['title'];
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];


        $result = $this->getWlconfig($type);

        $data['tab_list'] = $result['list'];

        $data['tab_type'] = $type;
        $data['tab_name'] = $result['row']['title'];

        //后台访问日志
        $this->log_admin_model->logs('查询《香草传奇》游戏新春主题营销活动邮寄名单',1);

        $this->load->view('admin/wlnewyearTheme', $data);
    }
    function sdTheme()
    {

        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '2020年《香草传奇》游戏双旦主题营销活动名单';
        $url_forward = $this->baseurl . '/sdTheme?';

        // 查询条件
        $where = '1=1 ';

        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:1;
        $where .= " and title='sd' and prize= "."'{$type}' ";

        $keywords = trim($_REQUEST['keywords']);

        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_record",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_record','*', $where, 'id DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时
        $status = ['未发货','已发货'];
        foreach($list as &$value)
        {

            $value['time'] = $value['time']?gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time):'';
            $value['status'] = $status[$value['status']];
            $res = $this->getWlconfig($value['prize']);
            $value['prize'] = $res['row']['title'];
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];


        $result = $this->getWlconfig($type);

        $data['tab_list'] = $result['list'];

        $data['tab_type'] = $type;
        $data['tab_name'] = $result['row']['title'];

        //后台访问日志
        $this->log_admin_model->logs('查询2020年《香草传奇》游戏双旦主题营销活动邮寄名单',1);

        $this->load->view('admin/wlsdTheme', $data);
    }
    function ortherTheme()
    {

        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $this->name = '2020年9-12月香草传奇游戏常规活动方案中奖名单';
        $url_forward = $this->baseurl . '/ortherTheme?';

        // 查询条件
        $where = '1=1 ';

        $type = $_REQUEST['tab_type']?$_REQUEST['tab_type']:1;
        $where .= " and title='orther' and prize= "."'{$type}' ";

        $keywords = trim($_REQUEST['keywords']);

        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";

        }


        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count("wl_record",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('wl_record','*', $where, 'id DESC', $this->per_page, $offset);
        $day = 25569;//excel和php之间相差的时间
        $time = 24 * 60 * 60;//一天24小时
        $status = ['未发货','已发货'];
        foreach($list as &$value)
        {

            $value['time'] = $value['time']?gmdate('Y-m-d H:i:s', ($value['time'] - $day) * $time):'';
            $value['status'] = $status[$value['status']];
            $res = $this->getWlconfig($value['prize']);
            $value['prize'] = $res['row']['title'];
            unset($value['id'],$value['time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = [
            'openid' => 'openID',
            'truename' => '用户姓名',

        ];


        $result = $this->getWlconfig($type);

        $data['tab_list'] = $result['list'];

        $data['tab_type'] = $type;
        $data['tab_name'] = $result['row']['title'];

        //后台访问日志
        $this->log_admin_model->logs('查询2020年9-12月香草传奇游戏常规活动方案中奖名单',1);

        $this->load->view('admin/wlortherTheme', $data);
    }


}
