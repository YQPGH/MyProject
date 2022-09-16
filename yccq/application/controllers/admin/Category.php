<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * 栏目控制器
 */
include 'Content.php';

class Category extends Content
{
    function __construct()
    {
        $this->name = '栏目';
        $this->control = 'category';
        $this->model_name = 'category_model';
        $this->list_view = 'category_list'; // 列表页
        $this->add_view = 'category_add'; // 添加页

        parent::__construct();
        $this->baseurl = site_url('admin/category/');
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
        $data['tree'] = $this->model->get_select();
        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $data['value'] = $this->model->row($id);
        $data['tree'] = $this->model->get_select($data['value']['parentid']);

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
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
	




