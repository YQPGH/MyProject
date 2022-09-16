<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Mouse extends Content{

    function __construct(){
        $this->name = '叠老鼠';
        $this->control = 'mouse';
        $this->list_view = 'admin/mouse_list'; //用户信息列表页
        $this->prize_view = 'admin/mouse_prize_list'; //奖品配置列表页
        $this->add_view = 'admin/mouse_prize_add'; //添加页
        parent ::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/mouse/');
        $this->load->model('admin/mouse_model','model');
    }

    //叠老鼠纪录
    function index(){
//        if(!permission('SYS_diemouse','read')) show_msg('没有操作权限！');
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
//        $query_count['num'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_diemouse a ",$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_diemouse a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
            $value['type'] = '奖券';
        }
        $data['list'] = $list;

        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询叠老鼠记录',1);
        $this->load->view( $this->list_view, $data);

    }

    function diemouse_config(){
        $_SESSION['nav'] = 3;
//        if(!permission('SYS_diemouse','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . 'diemouse_config?';

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

        $query_count['num'] = $this->model->table_count("zy_diemouse_config_test a ",$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->table_lists('zy_diemouse_config_test a','a.*', $where, 'id ASC', $this->per_page, $offset);

        $data['list'] = $list;

        // 搜索
        $data['fields'] = ['a.lv' => '用户等级'];
        $this->load->view( $this->prize_view, $data);
    }

    // 添加
    public function add()
    {
        $_SESSION['nav'] = 3;
//        if(!permission('SYS_diemouse','write')){
//            show_msg('没有操作权限！');
//        }
        $value['catid'] = intval($_REQUEST['catid']);

        $data['value'] = $value;

        $this->load->view($this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $_SESSION['nav'] = 3;
//        if(!permission('SYS_diemouse','write')){
//            show_msg('没有操作权限！');
//        }
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view( $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
//
//        if(!permission('SYS_diemouse','write')){
//            show_msg('没有操作权限！');
//        }
        $id = $this->input->post('id');
        $id = check_id($id);

        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改奖励信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加奖励信息',1);
            show_msg('添加成功！', 'diemouse_config');
        }
    }


    public function delete()
    {

        parent::delete();
    }


}
