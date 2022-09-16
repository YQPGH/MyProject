<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 节假日活动管理
 */
include_once 'Content.php';

class Holiday_activities extends Content
{
    function __construct()
    {
        $this->name = '国庆活动记录';
        $this->control = 'holiday_activities';
        $this->mid_autumn_view = 'admin/mid_autumn_list'; // 列表页
        $this->national_view = 'admin/national_list'; // 列表页
        $this->christmas_view = 'admin/christmas_list'; // 列表页
        $this->newyear_view = 'admin/newyear_list'; // 列表页
        $this->spring_view = 'admin/spring_list'; // 列表页
        $this->prize_view = 'admin/holiday_config_list'; // 列表页
        $this->boss_view = 'admin/log_boss_list'; // 列表页
        $this->add_view = 'admin/holiday_prize_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/holiday_activities/');
        $this->load->model('admin/holiday_activities_model', 'model');
    }

    // 首页
    public function index()
    {

        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
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
        $query_count['num'] = $this->model->count($where);
//        $query_count['num'] = $this->model->table_count('',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach($list as &$v){
            $row = $this->model->get_row('zy_shop ','name',['shopid'=>$v['shopid']]);
            $v['shop_name'] = $row['name'];
        }
        $data['list'] = $list;
        // 搜索
//        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询奖品信息',1);
        $this->load->view( $this->prize_view, $data);
    }

    //扫码获取原料奖励配置
    function fragment_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] = $url_forward = $this->baseurl . 'fragment_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 5;
        //$type2 = $_REQUEST['type2'] = 'scan';
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        /*if ($type2) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type2='$type2' ";
        }*/
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //每周任务奖励配置
    function week_task_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] = $url_forward = $this->baseurl . 'week_task_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 77;
        //$type2 = $_REQUEST['type2'] = 'scan';
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        /*if ($type2) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type2='$type2' ";
        }*/
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //拉新奖励配置
    function laxin_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] = $url_forward = $this->baseurl . 'laxin_config?';
        // 查询条件
        $where = '1=1 ';
        //$type1 = $_REQUEST['type1'] = 77;
        $type2 = $_REQUEST['type2'] = 'lx';
        /*if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }*/
        if ($type2) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type2='$type2' ";
            $data['type2'] = 'lx';
        }else{
            $data['type2'] = '';
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //广告奖励配置
    function advert_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . 'advert_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 78;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //挑战boss奖励配置
    function boss_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . 'boss_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 79;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //中秋活动奖励配置
    function mid_autumn_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . 'mid_autumn_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 80;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }


    //国庆活动奖励配置
    function national_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . '/national_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 81;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //圣诞活动奖励配置
    function christmas_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . '/christmas_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 82;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //元旦活动奖励配置
    function newyear_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . '/newyear_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 83;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }

    //春节活动奖励配置
    function spring_config(){
        $this->load->model('admin/prize_model');
        $_SESSION['nav'] = 7;
        $data['url'] =  $url_forward = $this->baseurl . '/spring_config?';
        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'] = 84;
        if ($type1) {
            //$data['type1'] = $type1;
            //$url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
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
        $data['count'] = $this->prize_model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type = config_item('prize_type');
        $list = $this->prize_model->lists('*', $where, 'type1,id', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type[$value['type1']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称', 'id' => '编号'];

        $this->load->view('admin/common_prize_list', $data);
    }
//七夕活动
    function qixiList(){

        $_SESSION['nav'] = 6;
        $data['url'] =  $url_forward = $this->baseurl . 'qixiList?';
        // 查询条件
        $where = '1=1 ';

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
        $data['count'] = $this->model->table_count('zy_qixi_prize_record  a',$where);

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $where .= " and a.uid=b.uid";
        // 列表数据
        $list = $this->model->table_lists('zy_qixi_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.id ASC', $this->per_page, $offset);
        $role = ['','织女','牛郎'];
        $status = ['未相会','已相会'];

        foreach ($list as &$value) {

            if($value['type'])
            {
                $prize = $this->db->query("select name,shop1_total from  zy_prize  WHERE id=? ",[$value['pid']])->row_array();
                $value['shop'] = $prize['name'];
                $value['shop_num'] = $prize['shop1_total'];
            }
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['b.name' => '昵称'];

        $this->load->view('admin/qixi_list', $data);
    }
    //中秋活动列表
    function mid_autumn_list(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/mid_autumn_list?';

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
        $where .= "  and a.title=8 ";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('zy_prize_record a ',$where);

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= 'and a.uid=b.uid';//获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.id ASC', $this->per_page, $offset);
        foreach($list as &$value){
            $prize = $this->db->query("select shandian,money,name,shop1,shop1_total from  zy_prize  WHERE id=? ",[$value['pid']])->row_array();
            $value['shop'] = '';
            $value['shop_num'] = 0;
            if($prize['shop1'])
            {
                $value['shop'] = $prize['name'];
                $value['shop_num'] = $prize['shop1_total'];
            }

            $value['money'] = $prize['money'];
            $value['shandian'] =$prize['shandian'];

        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动记录',1);

        $this->load->view($this->mid_autumn_view, $data);
    }

    //国庆活动列表
    function national_list(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/national_list?';

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
        //$data['count'] = $this->model->table_count('zy_national_day a',$where);
        //$data['count'] = 1644;
        $sql = "SELECT COUNT(*) as num  FROM zy_national_day a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_national_day a,zy_user b','a.*,b.nickname',$where, 'a.id ASC', $this->per_page, $offset);
        foreach($list as &$value){

            $row = $this->model->get_row('zy_holiday_config','*',['type'=>'national']);
            $sql = "select name from zy_shop WHERE shopid in($row[shopid]) ";
            $shop = $this->db->query($sql)->result_array();
            foreach($shop as $v){
                $value['type'][] = $v['name'];
            }
            $value['num'] = $row['shop_num'];
            $value['shandian'] = $row['shandian'];
            $value['money'] = $row['money'];

        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询国庆活动记录',1);

        $this->load->view($this->national_view, $data);
    }

    //圣诞活动列表
    function christmas_list(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/christmas_list?';

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
        $data['count'] = $this->model->table_count('log_prize a ',$where);
//        $sql = "SELECT COUNT(*) as num  FROM zy_christmas_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
//        $count_result = $this->db->query($sql)->row_array();
//        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= 'and a.uid=b.uid';//获取昵称
        // 列表数据
        $list = $this->model->table_lists('log_prize a,zy_user b','a.*,b.nickname',$where, 'a.id ASC', $this->per_page, $offset);
        foreach($list as $key=>&$value){
            $prize = $this->model->get_row('zy_prize','*',array('id'=>$value['prize_id']));

            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询圣诞活动记录',1);

        $this->load->view($this->christmas_view, $data);
    }

    //元旦活动列表
    function newyear_list(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/newyear_list?';

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

//        $start_time = '2019-01-01 00:00:00';
//        $stop_time = '2019-01-07 23:59:59';
//        $where .= "and a.add_time> '$start_time' and a.add_time<'$stop_time' and a.type=27";
        //$where .= "and a.pid=10";
        // URL及分页
        $offset = intval($_GET['per_page']);
        //$data['count'] = $this->model->table_count('zy_newyear_prize_record a',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_newyear_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= ' and a.uid=b.uid';
        // 列表数据
        $list = $this->model->table_lists('zy_newyear_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as $key=>&$value){
            $prize = $this->model->get_row('zy_prize','*',array('id'=>$value['prizeid']));
            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询元旦活动记录',1);

        $this->load->view($this->newyear_view, $data);
    }

    //春节活动列表
    /*function spring_list(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/spring_list?';

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

        $where .= "and a.pid=13 ";
        // URL及分页
        $offset = intval($_GET['per_page']);
        //$data['count'] = $this->model->table_count('zy_holiday_test_record a',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_holiday_test_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_holiday_test_record a,zy_user b','a.*,b.nickname',$where, 'a.id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $sql = "select h.shopid,h.shop_num,h.shandian,h.money,s.name  from zy_holiday_config h,zy_shop s WHERE h.id=? AND h.shopid=s.shopid";
            $row = $this->db->query($sql,[13])->row_array();

            $value['shop'] = $row['name'];
            $value['num'] = $row['shop_num'];
            $value['shandian'] = $row['shandian'];
            $value['money'] = $row['money'];
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询春节活动记录',1);

        $this->load->view($this->spring_view, $data);
    }*/

    //叠鼠列表
    function spring_list(){
        $_SESSION['nav'] = 6;
        $this->name = '春节福鼠记录';
        $url_forward = $this->baseurl . 'spring_list?';
        $this->baseurl = site_url('admin/holiday_activities/spring_list');
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
        //$data['count'] = $this->model->table_count('zy_boss_prize_record a ',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_diemouse_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_diemouse_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.add_time asc', $this->per_page, $offset);
        foreach ($list as $key => $value) {
            $prize = $this->model->get_row('zy_prize','*',array('id'=>$value['prizeid']));
            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询叠老鼠记录',1);

        $this->load->view($this->boss_view, $data);
    }

    //挑战boss列表
    function boss_list(){
        $_SESSION['nav'] = 6;
        $this->name = '探索记录';
        $url_forward = $this->baseurl . '/boss_list?';
        $this->baseurl = site_url('admin/holiday_activities/boss_list');
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
        //$data['count'] = $this->model->table_count('zy_boss_prize_record a ',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_boss_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_boss_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.add_time asc', $this->per_page, $offset);
        foreach ($list as $key => $value) {
            $prize = $this->model->get_row('zy_prize','*',array('id'=>$value['prizeid']));
            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询挑战boss记录',1);

        $this->load->view($this->boss_view, $data);
    }
    //春节列表
    function leaf_list(){
        $_SESSION['nav'] = 6;
        $this->name = '春节活动记录';
        $url_forward = $this->baseurl . '/leaf_list?';
        $this->baseurl = site_url('admin/holiday_activities/leaf_list');
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
        $data['count'] = $this->model->table_count('zy_leaf a ',$where);
//        $sql = "SELECT COUNT(*) as num  FROM zy_leaf a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
//        $count_result = $this->db->query($sql)->row_array();
//        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_leaf a,zy_user b','a.*,b.nickname',$where, 'a.id desc', $this->per_page, $offset);
        foreach ($list as &$value) {
            $row = $this->db->query("select * from  zy_leaf_prize_record where uid=?",[$value['uid']])->row_array();
            $value['money'] = $row['money'];
            $value['shandian'] = $row['shandian'];
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询春节活动记录',1);

        $this->load->view('admin/leaf_list', $data);
    }

    //    添加
    function add(){
        $_SESSION['nav'] = 7;
//        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view($this->add_view, $data);
    }

    // 添加
    public function common_prize_add()
    {
        $_SESSION['nav'] = 7;
        $value['total'] = 1000;
        $data['value'] = $value;
        $data['type2'] = $this->input->get('type2');
        $this->load->view('admin/common_prize_add', $data);
    }

    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
//        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $value = $this->model->row($id);
        $data['value'] = $value;
        $this->load->view($this->add_view,$data);
    }

    // 编辑
    public function common_prize_edit()
    {
        $_SESSION['nav'] = 7;
        $this->load->model('admin/prize_model');
        $id = $_GET['id'];
        // 这条信息
        $value = $this->prize_model->row($id);
        $data['value'] = $value;

        $this->load->view('admin/common_prize_add', $data);
    }

    //保存
    function save(){
//        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加成功',1);
            show_msg('添加成功！', 'index');
        }

    }

    // 删除
    public function delete(){
        parent::delete();
    }


    function config_list1()
    {
        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/config_list?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }
        $where .= "and type = 'month5'";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);


        // 列表数据
        $list = $this->model->table_lists('zy_holiday_config','*',$where, 'id desc', $this->per_page, $offset);

        foreach ($list as &$value) {
            $shop = $this->db->query("select `name` from zy_shop where shopid=?",[$value['shopid']])->row_array();

            $value['shop'] = $shop['name'];
            if($value['money'])
                $value['shop'] = '银元';
            if($value['shandian'])
                $value['shop'] = '闪电';
        }
        $data['list'] = $list;

        //搜索
        $data['fields'] = ['name' => '名称', 'shop1' => '编号'];
        //后台访问日志
        $this->log_admin_model->logs('查询奖励信息',1);
        $this->load->view('admin/holiday_config', $data);
    }

    function config_list2()
    {
        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/config_list2?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }
        $where .= " and type='national'";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('zy_holiday_config','*',$where, 'id desc', $this->per_page, $offset);

        foreach ($list as &$value) {
            $shop = $this->db->query("select `name` from zy_shop where shopid=?",[$value['shopid']])->row_array();

            $value['shop'] = $shop['name'];
            if($value['money'])
                $value['shop'] = '银元';
            if($value['shandian'])
                $value['shop'] = '闪电';
        }
        $data['list'] = $list;

        //搜索
        $data['fields'] = ['name' => '名称', 'shop1' => '编号'];
        //后台访问日志
        $this->log_admin_model->logs('查询奖励信息',1);
        $this->load->view('admin/holiday_config', $data);
    }

    function config_list3()
    {
        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/config_list3?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
        }
        $keywords = trim($_REQUEST['keywords']);
        $keywords = check_str($keywords);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $url_forward .= "&keywords=" . rawurlencode($keywords);
            $keywords = $this->db->escape_like_str($keywords);

            $field = $_REQUEST['field'];
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }
        $where .= " and type='newyear'";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $list = $this->model->table_lists('zy_holiday_config','*',$where, 'id desc', $this->per_page, $offset);

        foreach ($list as &$value) {
            $shop = $this->db->query("select `name` from zy_shop where shopid=?",[$value['shopid']])->row_array();

            $value['shop'] = $shop['name'];
            if($value['money'])
                $value['shop'] = '银元';
            if($value['shandian'])
                $value['shop'] = '闪电';
        }
        $data['list'] = $list;

        //搜索
        $data['fields'] = ['name' => '名称', 'shop1' => '编号'];
        //后台访问日志
        $this->log_admin_model->logs('查询奖励信息',1);
        $this->load->view('admin/holiday_config', $data);
    }
}
