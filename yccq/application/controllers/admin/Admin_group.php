<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 角色
 */
include_once 'Content.php';

class Admin_group extends Content
{
    function __construct()
    {
        $this->name = '管理员';
        $this->control = 'admin_group';
        $this->list_view = 'admin_group_list'; // 列表页
        $this->add_view = 'admin_group_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 4;
        $this->baseurl = site_url('admin/admin_group/');
        $this->load->model('admin/admin_group_model', 'model');
        $this->load->model('admin/menu_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Role','read')) show_msg('没有操作权限！');
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
        $istop_arr = config_item('istop');
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
            $value['menu_names'] = $this->menu_model->get_names($value['menu_ids']);
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = array('title' => '角色名称');

        //后台访问日志
        $this->log_admin_model->logs('查询角色信息',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Role','write')){
            show_msg('没有操作权限！');
        }
        $value['catid'] = $_REQUEST['catid'];
        $value['catid'] = check_id($value['catid']);

        $data['value'] = $value;
        $data['parents'] = $this->menu_model->get_child(0);
        $data['ids'] = [];

        //$data['menu'] = $this->menu_model->get_tree_checkbox();

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Role','write')){
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

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Role','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = trims($this->input->post('value'));
        

        if (empty($data['title'])) {
            show_msg('角色名称不能为空');
        }

        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改角色',1);
            show_msg('修改成功！', 'index');
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加角色',1);
            show_msg('添加成功！', 'index');
        }
    }

    public function priv_fenpei()
    {
        $id = $this->input->get('group_id');
        $id = check_id($id);
        $group = $this->model->row($id);
        //var_dump($group);

        $data['group']['read'] = explode(',', $group['priv_read']);
        $data['group']['write'] = explode(',', $group['priv_write']);
        $data['group']['del'] = explode(',', $group['priv_del']);

        $this->load->model('admin/admin_priv_model','priv_model');
        $data['priv_list'] = $this->priv_model->lists('*', array(), 'id DESC', 100, $offset = 0);

        $data['id'] = $id;
        $this->load->view('admin/admin_priv_fenpei',$data);
    }

    public function fenpeiSave()
    {
        if(!permission('SYS_Role','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);

        if(is_array($this->input->post('read'))) $data['priv_read'] = implode(',', $this->input->post('read'));
        if(is_array($this->input->post('write'))) $data['priv_write'] = implode(',', $this->input->post('write'));
        if(is_array($this->input->post('del'))) $data['priv_del'] = implode(',', $this->input->post('del'));
        $data['update_time'] = t_time();

        $this->model->update($data,$id);
        //后台访问日志
        $this->log_admin_model->logs('角色权限分配',1);
        show_msg('权限分配成功');
    }

    public function delete()
    {
        if(!permission('SYS_Role','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
