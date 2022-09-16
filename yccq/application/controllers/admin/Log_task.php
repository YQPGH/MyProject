<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 每日任务领奖记录
 */
include_once 'Content.php';

class Log_task extends Content
{
    function __construct()
    {
        $this->name = '每日任务领奖记录';
        $this->control = 'Log_task';
        $this->list_view = 'log_task_list'; // 列表页
        $this->add_view = ''; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/Log_task/');
        $this->load->model('admin/Log_task_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Task_log','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count("log_task_prize a" , $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .="and a.prize_id=b.id";
        // 列表数据
//        $list = $this->model->lists('*', $where, 'id DESC', $this->per_page, $offset);
        $list = $this->model->table_lists('log_task_prize a,zy_prize b','a.*,b.name description', $where, 'id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
            if($value['shopid']){
                $row = $this->model->get_row('zy_shop','name',['shopid'=>$value['shopid']]);
                $value['yanye'] = $row['name'];
            }
        }
        $list = $this->user_model->append_list($list);
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询每日任务领取记录',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

}
