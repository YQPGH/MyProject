<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 管理员
 */
include_once 'Content.php';

class Admin extends Content
{
    function __construct()
    {
        $this->name = '管理员';
        $this->control = 'admin';
        $this->list_view = 'admin_list'; // 列表页
        $this->add_view = 'admin_add'; // 添加页
        parent::__construct();
        $_SESSION['nav'] = 4;
        $this->baseurl = site_url('admin/admin/');
        $this->load->model('admin/admin_model', 'model');
        $this->load->model('admin/admin_group_model');

    }

    // 首页
    public function index()
    {
        $ci = &get_instance();
        // 超级管理 显示
        $admin = $ci->session->userdata('admin');
        // 查看是否有权限
        $ci->load->model('admin/admin_group_model');
        $result = $ci->admin_group_model->has_permission($admin['groupid'], 'SYS_Orders_config');

        if(!permission('SYS_Account','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/index?';

        $group = $this->admin_group_model->list_format();

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        $catid = check_id($catid);
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
            $field = check_str($field);
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }

        // URL及分页
        $offset = $_GET['per_page'];
        $offset = check_id($offset);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['group_name'] = $group[$value['groupid']];
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = array('username' => '用户名', 'truename' => '姓名', 'tel' => '电话');

        //后台访问日志
        $this->log_admin_model->logs('查询账号列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Account','write')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $data['group'] = $this->admin_group_model->list_format();

        $this->load->view('admin/'.$this->add_view, $data);
    }

    // 编辑
    public function edit()
    {

        if(!permission('SYS_Account','write')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;
        $data['group'] = $this->admin_group_model->list_format();

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit_dialog()
    {
        if(!permission('SYS_Account','write')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
    
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;
        $data['group'] = $this->admin_group_model->list_format();

        $this->url_forward($this->baseurl . '/edit_dialog?id=' . $id);
        $this->load->view('admin/admin_add_dialog', $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Account','write')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);

        $data = $this->security->xss_clean(trims($this->input->post('value')));

        if (empty($data['username'])) {
            show_msg('用户名不能为空');
        }
        preg_match('/(?=.*[a-z])(?=.*\d)(?=.*[#@!~%^&*])[a-z\d#@!~%^&*]{8,16}/i',$data[password],$match);
        $data[password] = $match[0];

        if(!$data[password]){
            show_msg('密码必须是8-16位且包含数字、字母、符号，请返回修改！');
        } else if ($data[password]) {
            $data[password] = get_password($data[password]);
        } else {
            unset ($data[password]);
        }

//        // 生成一张缩略图
//        if($data['thumb']) {
//            thumb( str_replace('/uploads/','uploads/',$data['thumb']), 220, 130 );
//        }

        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改账号信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加账号',1);
            show_msg('添加成功！', 'index');
        }
    }

    public function delete()
    {
        if(!permission('SYS_Account','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }


}
