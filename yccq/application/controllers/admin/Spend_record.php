<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 消费记录
 */
include_once 'Content.php';

class Spend_record extends Content
{
    function __construct()
    {
        $this->name = '消费记录';
        $this->control = 'Spend_record';
        $this->list_view = 'spend_record_list'; // 列表页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/Spend_record/');
        $this->load->model('admin/Spend_record_model', 'model');
        $this->load->model('admin/shop_model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Spend_record','read')) show_msg('没有操作权限！');
        $spend_type = config_item('spend_type');
        $url_forward = $this->baseurl . '/index?';
        // 查询条件
        $where = '1=1 AND type=1 ';
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
            if($field=='a.type'){
                foreach($spend_type as $key=>$value){
                    if($keywords==$value){
                        $keywords = $key;
                    }
                }
            }
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }
        
        // URL及分页
        $offset = intval($_GET['per_page']);
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("log_shop a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        //$shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('log_shop a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['spend_type_name'] = $spend_type[$value['type']];
            if($value['shopid']){
                $query = $this->model->get_row('zy_shop', ' name ' , "`shopid`='$value[shopid]'");
                $value['shop_name'] = $query['name'];
            }else{
                $value['shop_name'] = '';
            }
        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.type' => '花费类型'];
        //后台访问日志
        $this->log_admin_model->logs('查询购买记录',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    public function sale_index()
    {
        if(!permission('SYS_Spend__sale_record','read')) show_msg('没有操作权限！');
        $spend_type = config_item('spend_type');
        $url_forward = $this->baseurl . '/sale_index?';
        // 查询条件
        $where = '1=1 AND type=1 ';
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
            if($field=='a.type'){
                foreach($spend_type as $key=>$value){
                    if($keywords==$value){
                        $keywords = $key;
                    }
                }
            }
            $url_forward .= "&field=" . rawurlencode($field);
            $data['field'] = $field;
            $where .= " AND $field like '%{$keywords}%' ";
        }

        // URL及分页
        $offset = intval($_GET['per_page']);
        //$data['count'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count("log_shop a", $where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        //$shop = $this->shop_model->list_format();
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('log_shop a,zy_user b','a.*,b.nickname', $where, 'id ASC', $this->per_page, $offset);
        foreach ($list as &$value) {
            $value['spend_type_name'] = $spend_type[$value['type']];
            if($value['shopid']){
                $query = $this->model->get_row('zy_shop', 'name' , "`shopid`='$value[shopid]'");

                $value['shop_name'] = $query['name'];
            }else{
                $value['shop_name'] = '';
            }
        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID', 'a.type' => '花费类型'];
        //后台访问日志
        $this->log_admin_model->logs('查询出售记录',1);
        $this->load->view('admin/sale_record_list', $data);
    }

}
