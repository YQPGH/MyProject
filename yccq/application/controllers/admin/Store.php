<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 仓库
 */
include_once 'Content.php';

class Store extends Content
{
    function __construct()
    {
        $this->name = '仓库信息';
        $this->control = 'store';
        $this->list_view = 'store_list'; // 列表页
        $this->add_view = 'store_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/store/');
        $this->load->model('admin/store_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {

        if(!permission('SYS_Ru_Record','read')) show_msg('没有操作权限！');

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
        $query_count['num'] = $this->model->table_count("zy_store a",$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type1_arr = config_item('shop_type1');
        //$list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        $where .=  'AND a.total=1 AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_store a ,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
            $value['type1_name'] = $type1_arr[$value['type1']];
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.shopid' => '商品ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询入库记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 首页
    public function chu_list()
    {
        if(!permission('SYS_Chu_Record','read')) show_msg('没有操作权限！');

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
        $query_count['num'] = $this->model->table_count("zy_store a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $type1_arr = config_item('shop_type1');
        //$list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        $where .=  'AND a.total=1 AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_store a ,zy_user b','a.*,b.nickname', $where, 'id ASC', $this->per_page, $offset);

        foreach ($list as &$value) {
            $value['type1_name'] = $type1_arr[$value['type1']];
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.shopid' => '商品ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询出库记录',1);
        $this->load->view('admin/store_chu_list', $data);
    }

    public function store_upgrade_record(){

        if(!permission('SYS_Store_Upgrade_Record','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/store_upgrade_record?';

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
        $query_count['num'] = $this->model->table_count("zy_store_upgrade_record a",  $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_store_upgrade_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询仓库升级记录',1);
        $this->load->view('admin/store_upgrade_record_list', $data);
    }

}
