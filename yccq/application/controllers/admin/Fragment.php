<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *碎片管理
 *
 */
include_once 'Content.php';
class Fragment extends Content{

    function __construct(){
        $this->name='碎片管理';
        $this->control = 'Fragment';
        $this->list_view = 'fragment_int_list';
        $this->add_view = 'fragment_add';
        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/fragment/');
        $this->load->model('admin/fragment_model','model');

    }

    //首页
    function index(){

        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
        $data['count'] = $this->model->table_count('zy_fragment_record a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $name = config_item('suipian_type');

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list_record = $this->model->table_lists('zy_fragment_record a,zy_user b','a.*,b.nickname', $where, 'add_time DESC', $this->per_page, $offset);
//        $array = $this->model->table_lists('zy_fragment_share c,zy_user b','c.from_uid uid,c.suipian_type type,c.add_time,b.nickname', 'c.from_uid=b.uid', 'c.id DESC', $this->per_page, $offset);

        foreach($list_record as &$value){
//            if(!$value['resource']){
//                $value['resource'] = '好友赠送';
//            }
            $value['type'] = $name[$value['type']]['name'];
            $value['num'] =1;
        }

        $data['list'] = ($list_record);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];

        //后台访问日志
        $this->log_admin_model->logs('查询碎片入库信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    //扫码记录
    function scan(){

        $_SESSION['nav'] = 6;
        $url_forward = $this->baseurl . '/scan?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
        //$data['count'] = $this->model->table_count('zy_fragment_scan a',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_fragment_scan a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        //echo $this->db->last_query();exit;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);


        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_fragment_scan a,zy_user b','a.uid,a.type,a.status,a.add_time,a.update_time,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);

        $status = ['未领取','已领取'];
        $name = config_item('suipian_type');
        foreach($list as &$value){

            if($value['type'] == 0){
                $value['type'] = '无';
                $value['num'] = 0;
            }else{
                $value['type'] = $name[$value['type']]['name'];
                $value['num'] = 1;
            }
//            $value['status'] = $status[$value['status']];

        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询扫码碎片入库信息',1);

        $this->load->view('admin/fragment_scan_list', $data);
    }

    //出库记录
    function chu_list(){

        $url_forward = $this->baseurl . '/chu_list?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
        $where.= 'and share_type=2 and status=1 ';

        $data['count'] = $this->model->table_count('zy_fragment_share ',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->table_lists('zy_fragment_share ','*', $where, 'id DESC', $this->per_page, $offset);
//        $type = ['','赠送'];
        $name = config_item('suipian_type');
        foreach($list as &$value){
            $value['uid'] = $value['to_uid'];
            $value['friend_uid'] = $value['from_uid'];
            $value['name'] = $name[$value['suipian_type']]['name'];
            $value['type'] = '赠送';

        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['to_uid' => '用户id'];

        //后台访问日志
        $this->log_admin_model->logs('查询碎片出库信息',1);

        $this->load->view('admin/fragment_out_list', $data);
    }

    //库存管理
    function fragment_manage(){
        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/fragment_manage?';

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
        $where .= "AND a.type2='scan'";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('zy_prize a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.shop1=b.shopid';
        $list = $this->model->table_lists('zy_prize a,zy_shop b','a.id,a.money,a.shandian,a.shop1,a.shop1_total total,a.add_time,a.update_time,b.name,b.type2', $where, 'a.id DESC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.shop1' => '编号'];

        //后台访问日志
        $this->log_admin_model->logs('查询碎片库存信息',1);

        $this->load->view('admin/fragment_manage_list', $data);
    }

    function name_list(){
        if(!permission('SYS_Fragment_Name_List','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 8;
        $url_forward = $this->baseurl . '/name_list?';

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
        $data['count'] = $this->model->table_count('zy_fragment_prize_record a','1=1 ');
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= ' AND a.uid=b.uid';
        $list = $this->model->table_lists('zy_fragment_prize_record a,zy_user b','a.shopid,a.uid,a.add_time,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as &$value)
        {


            $sql = "select name from zy_shop  WHERE  shopid=? ";
            $prize  = $this->db->query($sql,[$value['shopid']])->row_array();

            $value['name'] = $prize['name'];

            unset($value['uid'],$value['shopid']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['b.openid' => 'openID'];
        //后台访问日志
        $this->log_admin_model->logs('查询集碎片赢京东好礼名单',1);

        $this->load->view('admin/fragment_name_list', $data);
    }

    //    添加
    function add(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Fragment_Manage','write')){
            show_msg('没有权限操作！');
        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view, $data);
    }
    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Fragment_Manage','write')){
            show_msg('没有权限操作！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);
        $value = $this->model->table_row('zy_prize',$id);

        $data['value'] = $value;

        $this->load->view('admin/'.$this->add_view,$data);
    }
    //保存
    function save(){
        if(!permission('SYS_Fragment_Manage','write')){
            show_msg('没有权限操作！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['update_time'] = t_time();
            $this->model->table_update('zy_prize',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->table_insert('zy_prize',$data);
            //后台访问日志
            $this->log_admin_model->logs('添加成功',1);
            show_msg('添加成功！', 'fragment_manage');
        }

    }



}