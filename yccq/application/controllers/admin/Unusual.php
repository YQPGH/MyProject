<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 异常数据
 */
include_once 'Content.php';

class Unusual extends Content
{
    function __construct()
    {
        $this->name = '异常数据';
        $this->control = 'unusual';
        $this->list_view = 'unusual_list'; // 列表页
        $this->add_view = 'unusual_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/unusual/');
        $this->load->model('admin/unusual_model', 'model');

    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Unusual_Record','read')) show_msg('没有操作权限！');

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
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("zy_unusual a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_unusual a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询数据异常记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Unusual_Record','write')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Unusual_Record','write')) show_msg('没有操作权限！');
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
        if(!permission('SYS_Unusual_Record','write')) show_msg('没有操作权限！');

        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (empty($data['title'])) show_msg('标题不能为空');

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

    public function delete()
    {
        if(!permission('SYS_Unusual_Record','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
