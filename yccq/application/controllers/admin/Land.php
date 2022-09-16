<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 土地
 */
include_once 'Content.php';

class Land extends Content
{

    function __construct()
    {
        $this->name = '土地信息';
        $this->control = 'land';
        $this->list_view = 'land_list'; // 列表页
        $this->add_view = 'land_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/land/');
        $this->load->model('admin/land_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Land','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count(" zy_land a ", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_land a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['land_name'] = $shop[$value['land_shopid']]['name'];
            $value['status'] = $value['status'] ? '种植中' : '空闲';
            if($value['seed_shopid'] && $value['yanye_shopid']){
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[seed_shopid]'");
                $value['seed_name'] = $query['name'];
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[yanye_shopid]'");
                $value['yanye_name'] = $query['name'];
            }
        }

        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.land_shopid' => '土地ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询土地信息',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    public function seed_record(){
        if(!permission('SYS_Seed_record','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/seed_record?';

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
        $query_count['num'] = $this->model->table_count(" zy_seed_record a ", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_seed_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
            $land_name = $this->db->query("select b.name,a.land_shopid from zy_land a,zy_shop b where a.land_shopid=b.shopid  and a.id=?; " ,$value['seed_shopid'])->row_array();
            $value['land_name'] = $land_name['name'];
            $value['land_shopid'] = $land_name['land_shopid'];
            if($value['seed_shopid'] && $value['yanye_shopid']){
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[seed_shopid]'");
                $value['seed_name'] = $query['name'];
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[yanye_shopid]'");
//                $query = $this->db->query("SELECT name FROM zy_shop WHERE shopid=$value[yanye_shopid]")->row_array();
                $value['yanye_name'] = $query['name'];
            }
        }

        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.land_shopid' => '土地ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询种植记录',1);

        $this->load->view('admin/seed_record_list', $data);
    }

    public function gather_record(){
        if(!permission('SYS_Seed_gather','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/gather_record?';

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
        $query_count['num'] = $this->model->table_count("zy_gather_record a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_gather_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['land_name'] = $shop[$value['land_shopid']]['name'];
            if($value['yanye_shopid']){
                $query = $this->model->get_row('zy_shop','name',"`shopid`='$value[yanye_shopid]'");
                $value['yanye_name'] = $query['name'];
            }
        }

        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.land_shopid' => '土地ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询采摘记录',1);

        $this->load->view('admin/gather_record_list', $data);
    }

    public function land_upgrade_record(){
        if(!permission('SYS_Land_upgrade','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/land_upgrade_record?';

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
        $query_count = $this->model->table_count(" zy_land_upgrade_record a ", $where);
        $data['count'] = $query_count;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_land_upgrade_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.land_shopid' => '土地ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询土地升级记录',1);

        $this->load->view('admin/land_upgrade_record_list', $data);
    }

}
