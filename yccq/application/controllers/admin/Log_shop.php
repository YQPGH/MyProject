<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 交易记录
 */
include_once 'Content.php';

class Log_shop extends Content
{
    function __construct()
    {
        $this->name = '交易记录';
        $this->control = 'Log_shop';
        $this->list_view = 'log_shop/index'; // 列表页
        $this->add_view = ''; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/Log_shop/');
        $this->load->model('admin/Log_shop_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Shop_log','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count("log_shop a ", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('log_shop a,zy_user b','a.*,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {

        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询交易记录',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 乐币排行
    public function money()
    {
        if(!permission('SYS_Ranking_money','read')) show_msg('没有操作权限！');
        $where = 'money<0';

        $keywords = trim($_REQUEST['keywords']);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $keywords = $this->db->escape_like_str($keywords);
            $field = $_REQUEST['field'];
            $data['field'] = $field;
            $where .= " AND $field = '{$keywords}' ";
        }


        $list = $this->model->group_lists('log_shop',' uid,SUM(money) money_sum', $where, 'uid','money_sum',100);

        foreach ($list as &$value) {

        }
        $data['list'] = $this->user_model->append_list($list);
        // 搜索
        $data['fields'] = ['uid' => '用户ID'];

        $data['count'] = count($list);

        //后台访问日志
        $this->log_admin_model->logs('查询乐币排行记录',1);

        $this->load->view('admin/log_shop/money', $data);
    }

    // 乐豆排行
    public function ledou()
    {
        if(!permission('SYS_Ranking_ledou','read')) show_msg('没有操作权限！');
        $where = 'ledou<0';

        $keywords = trim($_REQUEST['keywords']);
        if ($keywords) {
            $data['keywords'] = $keywords;
            $keywords = $this->db->escape_like_str($keywords);
            $field = $_REQUEST['field'];
            $data['field'] = $field;
            $where .= " AND $field = '{$keywords}' ";
        }

//        $list = $this->model->lists_sql("SELECT uid,SUM(ledou) ledou_sum FROM `log_shop`
//                                    WHERE  {$where}
//                                    GROUP BY uid
//                                    ORDER BY ledou_sum
//                                    LIMIT 100;");
        $list = $this->model->group_lists('log_shop',' uid,SUM(ledou) ledou_sum', $where, 'uid','ledou_sum',100);
        foreach ($list as &$value) {

        }
        $data['list'] = $this->user_model->append_list($list);
        // 搜索
        $data['fields'] = ['uid' => '用户ID'];

        $data['count'] = count($list);

        //后台访问日志
        $this->log_admin_model->logs('查询乐豆排行记录',1);

        $this->load->view('admin/log_shop/ledou', $data);
    }

}
