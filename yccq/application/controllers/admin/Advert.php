<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 广告管理
 */
include_once 'Content.php';

class Advert extends Content
{
    function __construct()
    {
        $this->name = '用户广告信息记录';
        $this->control = 'advert';
        $this->list_view = 'admin/advert_list'; // 列表页
        $this->add_view = 'admin/advert_config_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/advert/');
        $this->load->model('admin/advert_model', 'model');
    }
    
    // 首页
    public function index()
    {

        $_SESSION['nav'] = 6;
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

        // URL及分页
        $offset = intval($_GET['per_page']);
        //$data['count'] = $this->model->table_count('zy_ad_question_prize_record a ',$where);
        $sql = "SELECT COUNT(*) as num  FROM zy_ad_question_prize_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        $where .= 'and a.uid=b.uid';//获取昵称
        // 列表数据
        $list = $this->model->table_lists('zy_ad_question_prize_record a,zy_user b','a.*,b.nickname,b.openid',$where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as $key=>&$value){
            $prize = $this->model->get_row('zy_prize','*',array('id'=>$value['prize_id']));
            $list[$key]['yinyuan'] = $prize['money'] ? $prize['money'] : 0;
            $list[$key]['shandian'] = $prize['shandian'] ? $prize['shandian'] : 0;
            if($prize['shop1']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop1']));
                $list[$key]['shop1'] = $shop_name['name'];
                $list[$key]['shop1_total'] = $prize['shop1_total'];
            }
            if($prize['shop2']){
                $shop_name = $this->model->get_row('zy_shop','name',array('shopid'=>$prize['shop2']));
                $list[$key]['shop2'] = $shop_name['name'];
                $list[$key]['shop2_total'] = $prize['shop2_total'];
            }
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.uid' => '用户id','b.openid'=>'openid'];

        /*$url_forward = $this->baseurl . '/index?';

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
//        $query_count['num'] = $this->model->count($where);
        $query_count['num'] = $this->model->table_count('zy_advert_record a',$where);
        $data['count'] = $query_count['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);


        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_advert_record a,zy_user b','a.*,b.nickname,b.game_lv lv', $where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as &$value){
            $value['status'] = '领取';
            $sql = "select * from zy_advert_config WHERE id=?";
            $row = $this->db->query($sql,[$value['prizeid']])->row_array();
            if($row['type'] == 'shandian'){
                $value['type'] = '闪电 '.$row['num'];
            }else if($row['type'] == 'money'){
                $value['type'] = '银元 '.$row['num'];
            }else{
                $sql = "select `name` from zy_shop WHERE shopid=?";
                $res = $this->db->query($sql,[$row['shopid']])->row_array();
                $value['type'] = $res['name'].' '.$row['num'];
            }
            unset($value['prizeid']);
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID'];*/
        //后台访问日志
        $this->log_admin_model->logs('查询用户广告信息记录',1);
        $this->load->view( $this->list_view, $data);
    }

    //列表
    function config_list(){
        if(!permission('SYS_advert','read')) show_msg('没有操作权限！');
        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . 'config_list?';

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
//        $data['count'] = $this->model->count($where);
        $data['count'] = $this->model->table_count('zy_activity_list a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

//        $where .= 'and a.shopid=b.shopid';
        // 列表数据
        $list = $this->model->table_lists('zy_activity_list a','a.*',$where, 'a.arcrank DESC', $this->per_page, $offset);
//        $list = $this->model->lists('*',$where, 'id DESC', $this->per_page, $offset);
//        $status = ['发布中','已下线'];
//        foreach($list as &$v){
//
//            $v['status'] = $status[$v['status']];
//        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['name' => '活动名称'];

        //后台访问日志
        $this->log_admin_model->logs('查询广告信息',1);

        $this->load->view('admin/advert_config_list', $data);
    }


    //    添加
    function add(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_advert','read')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view($this->add_view, $data);
    }

    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_advert','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $sql = "select * from zy_activity_list WHERE id=?";
        $value = $this->db->query($sql,[$id])->row_array();
        $data['value'] = $value;
        $this->load->view($this->add_view,$data);
    }

    //保存
    function save(){
        if(!permission('SYS_advert','read')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['update_time'] = t_time();
            $this->model->table_update('zy_activity_list',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->table_insert('zy_activity_list',$data);
            //后台访问日志
            $this->log_admin_model->logs('添加成功',1);
            show_msg('添加成功！', 'config_list');
        }

    }

    public function delete(){
        parent::delete();
    }

    // 删除
    public function table_delete()
    {

        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->table_delete('zy_advert_record',$id);
            }
        } else {
            $this->model->delete('zy_advert_record',$id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

}
