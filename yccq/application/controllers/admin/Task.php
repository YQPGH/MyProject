<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *任务管理
 *
 */
include_once 'Content.php';
class Task extends Content{

    function __construct(){
        $this->name='任务记录';
        $this->control = 'Task';
        $this->list_view = 'task_list';
        $this->add_view = 'task_add';
        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/task/');
        $this->load->model('admin/task_model','model');
        $this->load->model('admin/setting_model');
    }

    //首页
    function index(){

        if(!permission('SYS_Task_record','read')) show_msg('没有操作权限！');
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


        $where .= " and a.reward_type!=4  and a.prize_id=b.id ";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('log_task_prize a,zy_prize b',$where);

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
//        $where .=" AND a.uid=c.uid " ;
        $list = $this->model->table_lists('log_task_prize a,zy_prize b','a.*,b.name,b.shop1,b.shop1_total',$where, 'a.id DESC', $this->per_page, $offset);

        foreach($list as &$value){
            $sql = "select nickname from zy_user WHERE uid=?";
            $row = $this->db->query($sql,[$value['uid']])->row_array();
            $task = $this->db->query("select description from zy_task WHERE prizeid=?",[$value['prize_id']])->row_array();
            $shop = $this->db->query("select `name` shop_name from zy_shop WHERE shopid=?",[$value['shop1']])->row_array();
            $value['title'] = $task['description'];
            $value['nickname'] = $row['nickname'];
            $value['money'] = '银元：'.$value['money'];
            $value['shandian'] = '闪电：'.$value['shandian'];
            $value['shop1'] = '商品：'.$shop['shop_name'];
            $value['shop1_total'] = '数量：'.$value['shop1_total'];
        }


        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id'];

        //后台访问日志
        $this->log_admin_model->logs('查询用户每日任务信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }



    //奖品列表
    function prize_list(){

        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/prize_list?';

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

        $task_today = $this->setting_model->get('task_today');
        $where.= "AND t.id in({$task_today})";

        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('zy_task t ',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where.= " AND  t.prizeid=p.id";
        // 列表数据
        $list = $this->model->table_lists('zy_task t, zy_prize p','t.prizeid,p.name,p.money,p.shandian,p.shop1 shopid,p.shop1_total shop_num,p.add_time,p.update_time',
            $where, 't.id DESC', $this->per_page, $offset);

        foreach($list as &$value){
            $row = $this->model->get_row('zy_shop','name',['shopid'=>$value['shopid']]);
            $value['shop_name'] = $row['name'];
        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['t.id' => '任务编号'];

        //后台访问日志
        $this->log_admin_model->logs('查询任务奖品信息',1);

        $this->load->view('admin/task_prize_list', $data);
    }


    //    添加
    function add(){
        $_SESSION['nav'] = 7;
//        if(!permission('SYS_Fragment_Manage','write')){
//            show_msg('没有权限操作！');
//        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view, $data);
    }
    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
//        if(!permission('SYS_Fragment_Manage','write')){
//            show_msg('没有权限操作！');
//        }
        $id = $this->input->get('id');
        $id = check_id($id);
        $sql = "select t.name shop_name,p.* from zy_shop t, zy_prize p WHERE p.shop1=t.shopid AND p.id='$id'";
        $value = $this->db->query($sql)->row_array();
        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view,$data);
    }
    //保存
    function save(){
//        if(!permission('SYS_Fragment_Manage','write')){
//            show_msg('没有权限操作！');
//        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['update_time'] = t_time();
            $this->model->table_update('zy_prize',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->table_insert('zy_prize',$data);
            //后台访问日志
            $this->log_admin_model->logs('添加成功',1);
            show_msg('添加成功！', 'prize_list');
        }

    }

    // 删除
    public function delete()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->table_delete($id);

            }
        } else {
            $this->model->table_delete($id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

}