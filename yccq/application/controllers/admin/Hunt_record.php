<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 每日问题记录
 */
include_once 'Content.php';

class Hunt_record extends Content
{
    function __construct()
    {
        $this->name = '挖宝通关记录';
        $this->control = 'Hunt_record';
        $this->list_view = 'Hunt_record_list'; // 列表页
        $this->add_view = ''; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/Hunt_record/');
        $this->load->model('admin/Hunt_record_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Hunt_record','read')) show_msg('没有操作权限！');
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
        //$where_count = $where.'AND reward_type=4';//抽奖的类型为4
        //$data['count'] = $this->model->count($where_count);
        $query_count['num'] = $this->model->table_count('zy_hunt_record a',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_hunt_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询挖宝游戏记录列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

}
