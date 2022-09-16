<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 好友
 */
include_once 'Content.php';

class Friend extends Content
{
    function __construct()
    {
        $this->name = '好友信息';
        $this->control = 'friend';
        $this->list_view = 'friend_list'; // 列表页
        $this->add_view = 'friend_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 5;
        $this->baseurl = site_url('admin/friend/');
        $this->load->model('admin/friend_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Friend','read')) show_msg('没有操作权限！');
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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $status = ['空闲', '加工中', '完成'];
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['status'] = $status[$value['status']];
            $query = $this->model->get_row('zy_user','nickname',"`uid`='$value[uid]'");
//            $query = $this->db->query("SELECT nickname FROM zy_user WHERE uid='$value[uid]'")->row_array();
            $value['my_name'] = $query['nickname'];
            $fquery = $this->model->get_row('zy_user','nickname',"`uid`='$value[friend_uid]'");
//            $query = $this->db->query("SELECT nickname FROM zy_user WHERE uid='$value[friend_uid]'")->row_array();
            $value['friend_name'] = $fquery['nickname'];
        }

        //$data['list'] = $this->shop_model->append_list($list);
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['uid' => '用户ID', 'friend_uid' => '好友ID', 'code'=>'随机码'];

        //后台访问日志
        $this->log_admin_model->logs('查询好友关系列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    //好友互访记录
    public function connect_list()
    {
        if(!permission('SYS_Friend_connect','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/connect_list?';

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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $status = ['空闲', '加工中', '完成'];
        $list = $this->model->lists('*', $where, 'id ASC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['status'] = $status[$value['status']];
            $query = $this->model->get_row('zy_user','nickname',"`uid`='$value[uid]'");
            $value['my_name'] = $query['nickname'];
            $query = $this->model->get_row('zy_user','nickname',"`uid`='$value[friend_uid]'");
            $value['friend_name'] = $query['nickname'];
        }
        //$data['list'] = $this->shop_model->append_list($list);
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['uid' => '用户ID', 'friend_uid' => '好友ID', 'code'=>'随机码'];

        //后台访问日志
        $this->log_admin_model->logs('查询互访记录',1);

        $this->load->view('admin/friend_connect_list', $data);
    }

    //种植好友互动管理
    function plant_index(){
        $url_forward = $this->baseurl . '/plant_index?';

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
        $query_count['num'] = $this->model->table_count('chongzi_send a',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $type = config_item('chongzi_type');
        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('chongzi_send a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach($list as &$value){
            $value['type'] = $type[$value['type']];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询互访记录',1);

        $this->load->view('admin/friend_plant_list', $data);
    }

    //烘烤好友互动管理
    function bake_index(){
        $url_forward = $this->baseurl . '/bake_index?';

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
        $query_count['num'] = $this->model->table_count('zy_fire a',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
//        $status = ['放火中', '已灭火'];
        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_fire a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
//        foreach($list as &$value){
//            $value['is_onfire'] = $status[$value['is_onfire']];
//        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询互访记录',1);

        $this->load->view('admin/friend_bake_list', $data);
    }

}
