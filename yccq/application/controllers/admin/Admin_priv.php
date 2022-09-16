<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 权限管理
 */
include_once 'Content.php';

class Admin_priv extends Content
{
    function __construct()
    {
        $this->name = '管理员';
        $this->control = 'admin_priv';
        $this->list_view = 'admin_priv_list'; // 列表页
        $this->add_view = 'admin_priv_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 4;
        $this->baseurl = site_url('admin/admin_priv/');
        $this->load->model('admin/admin_priv_model', 'model');
        $this->load->model('admin/menu_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Priv','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

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
        $offset = $this->input->get('per_page');
        $offset = check_id($offset);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = array('priv_name' => '权限名称');

        //后台访问日志
        $this->log_admin_model->logs('查询权限列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Priv','write')){
            show_msg('没有操作权限！');
        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $data['parents'] = $this->menu_model->get_child(0);
        $data['ids'] = [];

        //$data['menu'] = $this->menu_model->get_tree_checkbox();

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Priv','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $data['parents'] = $this->menu_model->get_child(0);
        $data['ids'] = explode(',', $value['menu_ids']);

        $this->load->view('admin/' . $this->add_view, $data);
    }

    public function getPrivById()
    {
        $id = $this->input->get('id');
        $id = check_id($id);
        // 这条信息
        $value = $this->model->row($id);
        t_json($value);

    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Priv','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data['pid'] = intval($this->input->post('pid'));
        $data['priv_name'] = trim($this->input->post('priv_name'));
        $data['priv_sign'] = trim($this->input->post('priv_sign'));

        if(empty($data['priv_name'])) {
            show_msg('权限名称不能为空');
        }

        if(empty($data['priv_sign'])) {
            show_msg('权限标识不能为空');
        }

        if ($id) { // 修改 ===========
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改权限',1);
            show_msg('修改成功！', 'index');
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加权限',1);
            show_msg('添加成功！', 'index');
        }
    }

    public function delete()
    {
        if(!permission('SYS_Priv','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }
}
