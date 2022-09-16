<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *活动数据分析
 *
 */
include_once 'Content.php';
class Data_analysis extends Content{

    function __construct(){
        $this->name='活动数据分析';
        $this->control = 'Data_analysis';
        $this->list_view = 'analysis_list';

        parent::__construct();
        $_SESSION['nav'] = 1;

        $this->baseurl = site_url('admin/data_analysis/');
        $this->load->model('admin/activity_model','model');

    }

    function analysisview()
    {
        $views = $_REQUEST['type'];
        $this->load->view('admin/'.$views);

    }



    //首页
    function index(){

//        if(!permission('SYS_Stat','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

        $data['year'] = $this->input->post('year');
        if(!$data['year']) $data['year'] = date('Y');

        $result = $this->model->month($data['year']);

        if ($result) {
            $data['dates'] = json_encode($result['dates']);
            $data['users'] = join(',', $result['users']);
            $data['active'] = join(',', $result['active']);
            $data['logins'] = join(',', $result['logins']);
            $data['user_gamelv'] = join(',', $result['user_gamelv']);
        }

        //后台访问日志
        $this->log_admin_model->logs('查询用户时长',1);
        $this->load->view('admin/' . $this->list_view, $data);

    }

    function leaf()
    {
        $url_forward = $this->baseurl . '/leaf?';
        $this->title = '奖品地域分析';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['name'];

        if ($type1) {
//            $data['name'] = $type1;
//            $url_forward .= '&name=' . $type1;
//            $where .= " AND name='$type1' ";

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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and status=1 ";
        // 列表数据
        $list = $this->model->table_lists('zy_activity_config','title,id,name', $where, 'id desc', $this->per_page, $offset);
//print_r($list);exit;

        foreach($list as $key=>&$value)
        {
            $data['title'][$value['name']] = $value['title'];

        }
        $data['list'] = ($list);
        // 搜索
//        $data['fields'] = ($list);



        //后台访问日志
        $this->log_admin_model->logs('查询活动数据信息',1);


//        print_r($data);exit;
        $this->load->view('admin/'.$this->list_view, $data);
    }

    function qixi()
    {

        if(!permission('SYS_Qixi','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 6;
        $this->name = '乘风破浪来见你';
        $this->baseurl = site_url('admin/activity/qixi');
        $url_forward = $this->baseurl . 'qixi?';

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

        // URL及分页
        $offset = intval($_GET['per_page']);
//        $data['count'] = $this->model->count($where);
        $data['count'] = 0;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $sql = "select p.id,p.`name`,p.shandian,p.money,p.total,s.type1 from zy_prize p,zy_shop s  WHERE s.shopid=p.shop1 and  p.type1=? ";
        $prize  = $this->db->query($sql,['qixi_prize'])->result_array();

        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num,shandian,money from zy_message a,zy_qixi_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND a.type='qixi'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        foreach($prize as &$value)
        {
            $count = $this->db->query(
                "select pid,count(*) as num from zy_qixi_prize_record WHERE pid=?;",[$value['id']]
            )->row_array();
            $value['num'] = $count['num'];
            $value['actual_num'] = $count['num'];
            $value['address_num'] = 0;
            foreach($lists as $v)
            {
                if($v['pid'] == $count['pid'])
                {
                    $value['address_num'] = $v['num'];
                    $value['actual_num'] = $value['address_num'];
                }

            }

        }

        $data['list'] = ($prize);
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询七夕活动列表信息',1);

        $this->load->view($this->count_view, $data);

    }


    function mid_autumn()
    {

        if(!permission('SYS_Midautumn','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 6;
        $this->name = '月圆情99 与你共婵娟';
        $this->baseurl = site_url('admin/activity/mid_autumn');
        $url_forward = $this->baseurl . '/mid_autumn?';

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

        // URL及分页
        $offset = intval($_GET['per_page']);
//        $data['count'] = $this->model->count($where);
        $data['count'] = 0;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num from zy_message a,zy_midautumn_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND a.type='midautumn'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        $sql = "select id,`name`,shandian,money,total from zy_prize  WHERE   type1=? ";
        $prize  = $this->db->query($sql,['mprize'])->result_array();
        foreach($prize as &$value)
        {
            $count = $this->db->query(
                "select pid,count(*) as num from zy_midautumn_prize_record  WHERE pid=?;",[$value['id']])->row_array();
            $value['num'] = $count['num'];
            $value['actual_num'] = $count['num'];
            $value['address_num'] = 0;
            if(count($lists))
            {
                foreach($lists as $v)
                {
                    if($v['pid'] == $count['pid'])
                    {
                        $value['address_num'] = $v['num'];
                        $value['actual_num'] = $value['address_num'];
                    }
                }
            }

        }

        $data['list'] = ($prize);
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动列表信息',1);

        $this->load->view($this->count_view, $data);

    }


    function test1()
    {

        $today = $this->time->today();

        $time  = strtotime($today);
        $sql = "SELECT COUNT(*)  num FROM ( SELECT COUNT(*) FROM zy_user_login WHERE   unix_timestamp(add_time)>='$time' GROUP BY uid) a having COUNT(*)>1";
        $logintimes = $this->db->query($sql)->row_array();
//        print_r($list);exit;


//        foreach($list as $value)
//        {
//
//        }
//        print_r();exit;

    }

    function test()
    {

            $sql = "select uid,last_time,logout_time from zy_user WHERE unix_timestamp(logout_time)>unix_timestamp(last_time)";
            $list = $this->db->query($sql)->result_array();
        print_r($list);exit;

    }



}