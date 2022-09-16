<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 新闻资讯控制器
 */
include_once 'Content.php';

class Guide_config extends Content
{
    function __construct()
    {
        $this->name = '游戏指引配置';
        $this->control = 'Guide_config';
        $this->list_view = 'guide_config_list'; // 列表页
        $this->add_view = 'guide_config_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/Guide_config/');
        $this->load->model('admin/Guide_config_model', 'model');
    }

    // 首页
    public function index()
    {
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
        $list = $this->model->lists('*', $where, 'id ASC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['id' => '指引步数'];

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $id = $this->input->get('id');
        $id = check_id($id);
       
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view('admin/'. $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        $id = $this->input->post('id');
        $id = check_id($id);

        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (empty($data['guide_step'])||empty($data['text'])) show_msg('标题不能为空');

//        // 生成一张缩略图
//        if($data['thumb']) {
//            thumb( str_replace('/uploads/','uploads/',$data['thumb']), 220, 130 );
//        }

        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            show_msg('修改成功！', $this->admin['url_forward']);
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            show_msg('添加成功！', 'index');
        }
    }

}
