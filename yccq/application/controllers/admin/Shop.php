<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  商品
 */
include_once 'Content.php';

class Shop extends Content
{
    function __construct()
    {
        $this->name = '商品';
        $this->control = 'shop';
        $this->list_view = 'shop_list'; // 列表页
        $this->add_view = 'shop_add'; // 添加页
        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/shop/');
        $this->load->model('admin/shop_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Shop','read')) show_msg('没有操作权限！');
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
        $type1_arr = config_item('shop_type1');
        $type2_arr = config_item('shop_type2');
        $status_arr = config_item('shop_status');
        $list = $this->model->lists('*', $where, 'type1,type2,shopid', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type1_arr[$value['type1']];
            $value['type2'] = $type2_arr[$value['type2']];
            $value['status'] = $status_arr[$value['status']];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['name' => '名称', 'shopid' => '编号'];

        //后台访问日志
        $this->log_admin_model->logs('查询普通商品信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    // 首页
    public function shen_mi()
    {
        if(!permission('SYS_Shop_shen_mi','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/shem_mi?';

        // 查询条件
        $where = '1=1 AND status=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
        $type1_arr = config_item('shop_type1');
        $type2_arr = config_item('shop_type2');
        $status_arr = config_item('shop_status');
        $list = $this->model->lists('*', $where, 'type1,type2,shopid', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['type1'] = $type1_arr[$value['type1']];
            $value['type2'] = $type2_arr[$value['type2']];
            $value['status'] = $status_arr[$value['status']];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['name' => '名称', 'shopid' => '编号'];

        //后台访问日志
        $this->log_admin_model->logs('查询神秘商品信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Shop','write')){
            show_msg('没有操作权限！');
        }
        $value['total'] = 1000;
        $data['value'] = $value;

        $this->load->view('admin/'.$this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Shop','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->model->row($id);

        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Shop','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (!$data['name'] || !$data['shopid']) show_msg('物品名称和编号不能为空');


        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改商品信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $detail = $this->model->detail($data['shopid']);
            if ($detail) show_msg('物品编号已经存在，请更换');
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加商品信息',1);
            show_msg('添加成功！', 'index');
        }
    }

    public function delete()
    {
        if(!permission('SYS_Shop','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
