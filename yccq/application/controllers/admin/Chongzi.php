<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 虫子记录
 */
include_once 'Content.php';

class Chongzi extends Content
{
    function __construct()
    {
        $this->name = '虫子记录';
        $this->control = 'chongzi';

        $this->send_view = 'send_record'; // 派遣记录

        $this->change_view = 'change_record'; //转换记录

        parent::__construct();
        $_SESSION['nav'] = 2;
        $this->baseurl = site_url('admin/chongzi/');
        $this->load->model('admin/chongzi_model', 'model');
    }
    
    // 派遣记录
    public function index()
    {
        if(!permission('SYS_Send_record','read')) show_msg('没有操作权限！');
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

        $query_count['num'] = $this->model->table_count('chongzi_send a',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $type = config_item('chongzi_type');

        // 列表数据
        $where.='and  a.uid=b.uid';
        $list = $this->model->table_lists('chongzi_send a,zy_user b',
            'a.*,b.uid as my_uid,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $status = ['派遣中','已清除'];
        foreach ($list  as  &$value) {

            $value['type'] = $type[$value['type']];
            $value['status'] = $status[$value['status']];

            $query = $this->model->get_row('chongzi_shouru','total',"`uid`='$value[uid]' and `index`='$value[id]' and type>0");
            $value['total'] = $query['total'];
            $query = $this->model->get_row('chongzi_shouru','total',"`uid`='$value[friend_uid]' and `index`='$value[id]'");
            $value['clear_total'] = $query['total'];

//            $value['my_name'] = $query['nickname'];
            $query = $this->model->get_row('zy_user','uid,nickname',"`uid`='$value[friend_uid]'");
            $value['friend_uid'] = $query['uid'];
//            $value['friend_name'] = $query['nickname'];

        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID','a.friend_uid' => '好友ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询虫子派遣记录列表',1);

        $this->load->view('admin/' . $this->send_view, $data);
    }


    //转换记录
    public function change_record()
    {
        if(!permission('SYS_Chongzi_Change_Record','read')) show_msg('没有操作权限！');
        $url_forward = $this->baseurl . '/change_record?';

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

        $query_count['num'] = $this->model->table_count('zy_change_record a',$where);

        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        // 列表数据
        $list = $this->model->table_lists('zy_change_record a,zy_user b','a.*,b.nickname', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];

        //后台访问日志
        $this->log_admin_model->logs('查询虫子转换记录列表',1);

        $this->load->view('admin/' . $this->change_view, $data);
    }
}
