<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 后台操作日志
 */
include_once 'Content.php';

class LogAdmin extends Content
{
    function __construct()
    {
        $this->name = '后台操作日志';
        $this->control = 'logadmin';
        $this->list_view = 'logadmin'; // 列表页
        $this->add_view = 'logadmin_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 4; //所属头部导航编号
        $this->baseurl = site_url('admin/logadmin/');
        $this->load->model('admin/log_admin_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Log','read')) show_msg('没有操作权限！');
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
        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        $data['list'] = $this->admin_model->append_list($list);

        // 搜索
        $data['fields'] = array('url' => '模块', 'ip' => 'IP', 'add_time' => '时间');

        //后台访问日志
        $this->log_admin_model->logs('查询后台操作日志列表',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }
}
