<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 培育中心
 */
include_once 'Content.php';

class Peiyu extends Content
{
    function __construct()
    {
        $this->name = '种子培育中心';
        $this->control = 'peiyu';
        $this->list_view = 'peiyu_list'; // 列表页
        $this->add_view = 'peiyu_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/peiyu/');
        $this->load->model('admin/peiyu_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Peiyu','read')) show_msg('没有操作权限！');
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

        $query_count['num'] = $this->model->table_count("zy_peiyu a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $status = ['空闲', '加工中', '完成'];
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_peiyu a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['status'] = $status[$value['status']];
            if($value['yanye1'] && $value['yanye2'] && $value['seed']){
                $query = $this->model->get_row('zy_shop', 'name' , "`shopid`='$value[yanye1]'");
//                $query = $this->db->query("SELECT name FROM zy_shop WHERE shopid=$value[yanye1]")->row_array();
                $value['yanye1_name'] = $query['name'];
                $query = $this->model->get_row('zy_shop', 'name' , "`shopid`='$value[yanye2]'");
//                $query = $this->db->query("SELECT name FROM zy_shop WHERE shopid=$value[yanye2]")->row_array();
                $value['yanye2_name'] = $query['name'];
                $query = $this->model->get_row('zy_shop', 'name' , "`shopid`='$value[seed]'");
//                $query = $this->db->query("SELECT name FROM zy_shop WHERE shopid=$value[seed]")->row_array();
                $value['seed_name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询种子培育记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
