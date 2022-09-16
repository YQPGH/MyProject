<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 配方研究所
 */
include_once 'Content.php';

class Peifang extends Content
{
    function __construct()
    {
        $this->name = '配方研究所';
        $this->control = 'peifang';
        $this->list_view = 'peifang_list'; // 列表页
        $this->add_view = 'peifang_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/peifang/');
        $this->load->model('admin/peifang_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Peifang','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count("zy_peifang a ",$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $status = ['空闲', '加工中', '完成'];
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_peifang a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['status'] = $status[$value['status']];
            if($value['peifang1'] && $value['peifang2'] && $value['peifang3'] && $value['peifang_high']){
                $query = $this->model->get_row("zy_shop",'name',  "`shopid`='$value[peifang1]'");
                $value['peifang1_name'] = $query['name'];
                $query = $this->model->get_row("zy_shop",'name',  "`shopid`='$value[peifang2]'");
                $value['peifang2_name'] = $query['name'];
                $query = $this->model->get_row("zy_shop",'name',  "`shopid`='$value[peifang3]'");
                $value['peifang3_name'] = $query['name'];
                $query = $this->model->get_row("zy_shop",'name',  "`shopid`='$value[peifang_high]'");
                $value['peifang_high_name'] = $query['name'];
            }
        }
        $data['list'] = $this->shop_model->append_list($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询配方研究记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

}
