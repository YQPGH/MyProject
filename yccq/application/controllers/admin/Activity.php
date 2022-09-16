<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *活动管理
 *
 */
include_once 'Content.php';
class Activity extends Content{

    function __construct(){
        $this->name='活动管理';
        $this->control = 'Activity';
        $this->list_view = 'activity_list';
        $this->add_view = 'activity_add';
        $this->count_view = 'admin/activity_count';
        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/activity/');
        $this->load->model('admin/activity_model','model');

    }

    //首页
    function index(){
        $url_forward = $this->baseurl . '/index?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where, 'id', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询活动列表信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }


    function qixi()
    {

        if(!permission('SYS_Qixi','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 6;
        $this->name = '乘风破浪来见你';
        $this->baseurl = site_url('admin/activity/qixi');
        $url_forward = $this->baseurl . '/qixi?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
//        $data['count'] = $this->model->count($where);
        $data['count'] = 0;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据

        $sql = "select p.id,p.`name`,p.shandian,p.money,p.total,s.type1 from zy_prize p,zy_shop s  WHERE s.shopid=p.shop1 and  p.type1=? ";
        $prize  = $this->db->query($sql,['qixi_prize'])->result_array();

        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num,shandian,money from zy_message a,zy_qixi_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND a.type='qixi'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        foreach($prize as &$value)
        {
            $count = $this->db->query(
                "select pid,count(*) as num from zy_qixi_prize_record WHERE pid=?;",[$value['id']]
            )->row_array();
            $value['num'] = $count['num'];
            $value['actual_num'] = $count['num'];
            $value['address_num'] = 0;
            foreach($lists as $v)
            {
                if($v['pid'] == $count['pid'])
                {
                    $value['address_num'] = $v['num'];
                    $value['actual_num'] = $value['address_num'];
                }

            }

        }

        $data['list'] = ($prize);
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询七夕活动列表信息',1);

        $this->load->view($this->count_view, $data);

    }


    function mid_autumn()
    {

        if(!permission('SYS_Midautumn','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 6;
        $this->name = '月圆情99 与你共婵娟';
        $this->baseurl = site_url('admin/activity/mid_autumn');
        $url_forward = $this->baseurl . '/mid_autumn?';

        // 查询条件
        $where = '1=1 ';
        $type1 = $_REQUEST['type1'];
        if ($type1) {
            $data['type1'] = $type1;
            $url_forward .= '&type1=' . $type1;
            $where .= " AND type1='$type1' ";
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
//        $data['count'] = $this->model->count($where);
        $data['count'] = 0;
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $lists = $this->db->query(
            "select c.pid,COUNT(c.id) as num from zy_message a,zy_midautumn_prize_record c
                WHERE  c.ticket_id<1 and c.type=1 AND a.type='midautumn'
                AND a.pid=c.id GROUP BY c.pid")->result_array();

        $sql = "select id,`name`,shandian,money,total from zy_prize  WHERE   type1=? ";
        $prize  = $this->db->query($sql,['mprize'])->result_array();
        foreach($prize as &$value)
        {
            $count = $this->db->query(
                "select pid,count(*) as num from zy_midautumn_prize_record  WHERE pid=?;",[$value['id']])->row_array();
            $value['num'] = $count['num'];
            $value['actual_num'] = $count['num'];
            $value['address_num'] = 0;
            if(count($lists))
            {
                foreach($lists as $v)
                {
                    if($v['pid'] == $count['pid'])
                    {
                        $value['address_num'] = $v['num'];
                        $value['actual_num'] = $value['address_num'];
                    }
                }
            }

        }

        $data['list'] = ($prize);
        // 搜索
        $data['fields'] = ['name' => '名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询中秋活动列表信息',1);

        $this->load->view($this->count_view, $data);

    }

    //    添加
    function add(){
        if(!permission('SYS_Activity','write')){
            show_msg('没有权限操作！');
        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view, $data);
    }
    //编辑
    function edit(){
        if(!permission('SYS_Activity','write')){
            show_msg('没有权限操作！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);
        $value = $this->model->row($id);

        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view,$data);
    }
    //保存
    function save(){
        if(!permission('SYS_Activity','write')){
            show_msg('没有权限操作！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (empty($data['name'])) show_msg('名称不能为空');
        if($id){  //修改
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改活动',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加活动',1);
            show_msg('添加成功！', 'index');
        }

    }
}