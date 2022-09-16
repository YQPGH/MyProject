<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 种植集能量管理
 */
include_once 'Content.php';

class Energytrees extends Content
{
    function __construct()
    {
        $this->name = '种植集能量';
        $this->type = 'trees';
        $this->control = 'energytrees';
        $this->list_view = 'admin/energytrees_list'; // 列表页
        $this->prize_view = 'admin/energytrees_prizelist'; // 抽奖记录页
        $this->gather_view = 'admin/energytrees_gatherlist'; //
        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/energytrees/');
        $this->load->model('admin/energytrees_model', 'model');
    }
    function getIdnum()
    {
        $sql = "select id from zy_activity_config WHERE `name`=?";
        $id = $this->db->query($sql,[$this->type])->row_array();
        return $id['id'];
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
        $query_count['num'] = $this->model->table_count('zy_trees_ball a',$where);

        $data['count'] = $query_count['num'];

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_trees_ball a,zy_user b','a.*,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);
        $item = ['生产中','可收取','停产中'];
        foreach($list as &$value){

           $value['addtime'] = t_time($value['addtime']);
           $value['status'] = 2;
           if($value['endtime'])
           {
               $value['status'] = time() - $value['endtime']>0?1:0;
           }
           $value['status'] = $item[$value['status']];

        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];
        //后台访问日志
        $this->log_admin_model->logs('查询能量用户信息',1);
        $this->load->view( $this->list_view, $data);
    }

    public function gather_list()
    {
        $this->name = "能量值信息";
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/gather_list?';

        // 查询条件
        $where = " 1=1 ";

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

        $query_count['num'] = $this->model->table_count('zy_trees_gatherrecord a',$where);

        $data['count'] = $query_count['num'];

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_trees_gatherrecord a ,zy_user b','a.*,b.nickname ', $where, 'a.id DESC', $this->per_page, $offset);
        $item = ['正常收取','能量流失'];

        foreach($list as &$value)
        {
            $value['addtime'] = t_time($value['addtime']);
            $value['updatetime'] = t_time($value['updatetime']);
            $value['type'] = $item[$value['type']];


        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询能量收取流失记录',1);
        $this->load->view( $this->gather_view, $data);

    }

    //列表
    function prize_list(){
        $this->name = '抽奖记录';
        if(!permission('SYS_Activity','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . 'prize_list?';

        // 查询条件
        $titlenum = $this->getIdnum();
        $where = "   a.title=$titlenum ";

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
        $data['count'] = $this->model->table_count('zy_prize_record a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $where .= ' and a.uid=b.uid';

        // 列表数据
        $list = $this->model->table_lists('zy_prize_record a,zy_user b','a.*,b.openid,b.nickname',$where, 'a.id DESC', $this->per_page, $offset);

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
        $this->log_admin_model->logs('查询能量球抽奖记录',1);

        $this->load->view($this->prize_view, $data);
    }
}
