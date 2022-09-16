<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 活动管理
 */
include_once 'Content.php';

class Midautumn extends Content
{
    function __construct()
    {
        $this->name = '用户游戏信息';
        $this->type = 'mid_autumn';
        $this->control = 'midautumn';
        $this->prize_view = 'admin/midautumn_prizelist'; //
        $this->compose_view = 'admin/midautumn_compose'; //
        $this->list_view = 'admin/midautumn_list'; //

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/midautumn/');
        $this->load->model('admin/base_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->table_count('zy_midautumn_player a',$where);
        $data['count'] = $query_count['num'];

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_midautumn_player a,zy_user b','a.*,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);


        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动用户记录',1);
        $this->load->view( $this->list_view, $data);
    }

  //
    public function compose_list()
    {
        $this->name = "月饼合成信息";
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/compose_list?';

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
        $query_count['num'] = $this->model->table_count('zy_midautumn_compose a',$where);
        $data['count'] = $query_count['num'];

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_midautumn_compose a,zy_user b','a.*,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);


        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动合成记录',1);
        $this->load->view( $this->compose_view, $data);
    }

    //列表
    function prize_list(){
        $this->name = '抽奖记录';
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . 'prize_list?';

        // 查询条件
        $where = " 1=1";

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
        $data['count'] = $this->model->table_count('zy_midautumn_prize_record a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $where .= ' and a.uid=b.uid';

        // 列表数据
        $list = $this->model->table_lists('zy_midautumn_prize_record a,zy_user b','a.*,b.openid,b.nickname',$where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as &$value)
        {
            $prize = $this->db->query("select name,money,shandian,shop1,shop1_total  from zy_prize where id=?",[$value['pid']])->row_array();

            $value['name'] = '';
            if($prize['shop1'])
            {
                $value['name'] = $prize['name'];
            }

            $value['money']  = $prize['money'];
            $value['shandian']  = $prize['shandian'];
            $value['shop1']  = $prize['shop1'];
            $value['shop1_total']  = $prize['shop1_total'];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];

        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动抽奖记录',1);

        $this->load->view($this->prize_view, $data);
    }




    

}
