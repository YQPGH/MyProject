<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  奖品配置
 */
include_once 'Content.php';

class Hunt_prize_config extends Content
{
    function __construct()
    {
        $this->name = '商品';
        $this->control = 'Hunt_prize_config';
        $this->list_view = 'hunt_prize_config_list'; // 列表页
        $this->add_view = 'hunt_prize_config_add'; // 添加页
        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/Hunt_prize_config/');
        $this->load->model('admin/Hunt_prize_config_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Hunt_config','read')) show_msg('没有操作权限！');
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
        $list = $this->model->lists('*', $where, 'id', $this->per_page, $offset);
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询挖宝游戏配置列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Hunt_config','write')) show_msg('没有操作权限！');
        $value['total'] = 1000;
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Hunt_config','write')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
       
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Hunt_config','write')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));

        if (!$data['pass']) show_msg('关卡不能为空');
        if($data['money']){
            $data['type1'] = 'money';
        }
        if($data['shop1']||$data['shop2']){
            $data['type1'] = 'peifang';
        }
        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改挖宝游戏配置',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加挖宝游戏配置',1);
            show_msg('添加成功！', 'index');
        }
    }

}
