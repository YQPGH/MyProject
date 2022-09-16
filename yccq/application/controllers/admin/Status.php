<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 仓库
 */
include_once 'Content.php';

class Status extends Content
{
    function __construct()
    {
        $this->name = '建筑状态';
        $this->control = 'status';
        $this->list_view = 'status_list'; // 列表页
        $this->add_view = 'status_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/status/');
        $this->load->model('admin/status_model', 'model');
        $this->load->model('admin/shop_model');
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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_status a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        
        // 列表数据
        $status = ['空闲', '加工中', '完成'];
        $where .=  'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_status a ,zy_user b','a.*,b.nickname',$where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['bake_status'] = $status[$value['bake_status']];
            $value['aging_status'] = $status[$value['aging_status']];
            $value['process_status'] = $status[$value['process_status']];
            $value['process_status_2'] = $status[$value['process_status_2']];
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        $this->load->view('admin/' . $this->list_view, $data);
    }

    //玩家制烟记录管理
    public function process_record(){

        if(!permission('SYS_Status_Process_Record','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/process_record?';

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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_process_record a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_process_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            //$value['land_name'] = $shop[$value['process_shopid']]['name'];
            if($value['process_shopid']){
                $query = $this->model->get_row('zy_shop','name', "`shopid`='$value[process_shopid]'");
                $value['name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.process_shopid' => '配方书ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询制烟记录',1);
        $this->load->view('admin/process_record_list', $data);
    }

    //烘烤记录管理
    public function bake_record(){

        if(!permission('SYS_Status_Bake_Record','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/bake_record?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_bake_record a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_bake_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            //$value['land_name'] = $shop[$value['process_shopid']]['name'];
            if($value['bake_shopid']){
                $query = $this->model->get_row('zy_shop','name', "`shopid`='$value[bake_shopid]'");
                $value['name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.bake_shopid' => '烘烤烟叶ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询烘烤记录',1);
        $this->load->view('admin/bake_record_list', $data);
    }

    //品鉴记录管理
    public function pinjian_record(){

        if(!permission('SYS_Status_Pinjian_Record','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/pinjian_record?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_pinjian_record a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_pinjian_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            //$value['land_name'] = $shop[$value['process_shopid']]['name'];
            if($value['shopid']){
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[shopid]'");
                $value['yan_name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.bake_shopid' => '烘烤烟叶ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询品鉴记录',1);
        $this->load->view('admin/pinjian_record_list', $data);
    }

    //醇化（贮藏）记录管理
    public function aging_record(){

        if(!permission('SYS_Status_Aging_Record','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/aging_record?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_aging_record a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_aging_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            //$value['land_name'] = $shop[$value['process_shopid']]['name'];
            if($value['aging_shopid']){
                $query = $this->model->get_row('zy_shop','name', "`shopid`='$value[aging_shopid]'");
                $value['yanye_name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.aging_shopid' => '贮藏烟叶ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询醇化记录',1);
        $this->load->view('admin/aging_record_list', $data);
    }

}
