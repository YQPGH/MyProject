<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 后台栏目
 */
include_once 'Content.php';

class Menu extends Content
{
    function __construct()
    {
        $this->name = '管理员';
        $this->control = 'menu';
        $this->list_view = 'menu_list'; // 列表页
        $this->add_view = 'menu_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 4;
        $this->baseurl = site_url('admin/menu/');
        $this->load->model('admin/menu_model', 'model');
    }

    // 首页
    public function index()
    {
        $data['tree'] = $this->model->get_tree();
        $this->url_forward($this->baseurl);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        $parent = $this->model->get_child(0);
        foreach ($parent as $value) {
            $data['parent'][$value['id']] = $value['name'];
        }

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $id = $this->input->get('id');
        $id = check_id($id);
        
        $data['value'] = $this->model->row($id);
        
        $parent = $this->model->get_child(0);
        foreach ($parent as $value) {
            $data['parent'][$value['id']] = $value['name'];
        }

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = $_POST['value'];
        $data = $this->security->xss_clean($this->input->post('value'));
        $data['sort'] = intval($data['sort']);

        if ($id) { // 修改
            if ($id == $data['parentid']) {
                show_msg('上级分类不能选自己！');
            }
            $this->model->update($data, $id);
            show_msg('修改成功！', $this->baseurl);
        } else { // 添加
            $this->model->insert($data);
            show_msg('添加成功！', $this->baseurl);
        }
    }

}
