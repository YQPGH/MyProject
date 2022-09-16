<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *订单排行管理
 *
 */
include_once 'Content.php';
class Order_rank extends Content{

    function __construct(){
        $this->name='订单排行管理';
        $this->control = 'order_rank';
        $this->list_view = 'order_rank_list';

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/order_rank/');
        $this->load->model('admin/order_rank_model','model');

    }

    function index(){

        if(!permission('SYS_Order_Rank','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . 'index?';

        // 查询条件
        $where = '1=1 ';
        $catid = $_REQUEST['catid'];
        if ($catid) {
            $data['catid'] = $catid;
            $url_forward .= '&catid=' . $catid;
            $where .= " AND catid='$catid' ";
        }
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
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
        //$data['count'] = $this->model->table_count('zy_boss_prize_record a ',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_ranking_order_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= "and a.uid=b.uid"; //获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_ranking_order_prize_record a,zy_user b','a.*,b.nickname',$where, 'a.ranking asc', $this->per_page, $offset);
        foreach ($list as $key => $value) {
            $prize = $this->model->get_row('zy_ranking_jf_prize_config','*',array('id'=>$value['pid']));
            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1_id']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1_id']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2_id']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2_id']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.nickname'=>'昵称'];

        //后台访问日志
        $this->log_admin_model->logs('查询订单排行信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    //首页
    /*function index(){

        if(!permission('SYS_Order_Rank','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $keywords = trim($_REQUEST['keywords']);
//        $keywords = check_str($keywords);
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
        $data['count'] = $this->model->table_count("zy_order_rank_record a",$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $list = $this->model->table_lists('zy_order_rank_record a','*',$where, 'a.jifen_1 desc', $this->per_page, $offset);
        foreach ($list as $key => $value) {
            $re = $this->model->get_row('zy_shop','name',array('shopid'=>$value['award'])); 
            $resule = $this->model->get_row('zy_user','nickname',array('uid'=>$value['uid']));               
            $list[$key]['name'] = $re['name'];
            $list[$key]['nickname'] = $resule['nickname'];
        }

        $data['list'] = ($list);
        $data['offset'] = $offset;
        //print_r($data);exit;
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];
        //$data['arr'] = $arr;
        //后台访问日志
        $this->log_admin_model->logs('查询订单排行信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }*/

}