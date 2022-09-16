<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 新闻资讯控制器
 */
include_once 'Content.php';

class Chengjiu extends Content
{
    function __construct()
    {
        $this->name = '成就获得记录';
        $this->control = 'chengjiu';
        $this->list_view = 'chengjiu_list'; // 列表页
        $this->add_view = 'chengjiu_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/chengjiu/');
        $this->load->model('admin/user_model', 'model');

    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Chengjiu','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        $catid = check_id($catid);
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
            $field = check_str($field);
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }

        // URL及分页
        $offset = $_GET['per_page'];
        $offset = check_id($offset);
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where,'id DESC', $this->per_page, $offset);
//        $list = $this->model->list_all($where, $this->per_page, $offset);
        $yannong_type = config_item('yannong_type');
        $zhiyan_type = config_item('zhiyan_type');
        $jiaoyi_type = config_item('jiaoyi_type');
        $pinjian_type = config_item('pinjian_type');
        foreach ($list as &$value) {

            $value['yannong_lv'] = $yannong_type[$value['yannong_lv']]['name'];
            $value['zhiyan_lv'] = $zhiyan_type[$value['zhiyan_lv']]['name'];
            $value['jiaoyi_lv'] = $jiaoyi_type[$value['jiaoyi_lv']]['name'];
            $value['pinjian_lv'] = $pinjian_type[$value['pinjian_lv']]['name'];
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = [
            'nickname' => '昵称',
            'uid' => 'uid',
           ];

        //后台访问日志
        $this->log_admin_model->logs('查询成就获得列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }


}
