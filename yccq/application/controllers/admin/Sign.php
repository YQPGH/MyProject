<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 签到记录
 */
include_once 'Content.php';

class Sign extends Content
{
    function __construct()
    {
        $this->name = '签到记录';
        $this->control = 'sign';
        $this->list_view = 'sign_list'; // 列表页
        $this->add_view = ''; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/sign/');
        $this->load->model('admin/sign_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Sign','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count("zy_sign a ",$where);
//        $query_count = $this->db->query("select count(*) as num from zy_sign a WHERE $where")->row_array();
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $istop_arr = config_item('istop');
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_sign a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['istop'] = $value['istop'] ? $istop_arr[$value['istop']] : '';
            if ($value['thumb']) $value['thumb'] = base_url($value['thumb']);
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询签到记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
