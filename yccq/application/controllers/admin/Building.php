<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 建筑管理
 */
include_once 'Content.php';

class Building extends Content
{
    function __construct()
    {
        $this->name = '建筑升级记录';
        $this->control = 'building';
        $this->list_view = 'admin/building_list'; // 列表页
        $this->add_view = 'admin/building_config_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/building/');
        $this->load->model('admin/building_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
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
        //$query_count['num'] = $this->model->count($where);
        //$query_count['num'] = $this->model->table_count('zy_building a',$where);
        //print_r($query_count);exit;
        //$data['count'] = $query_count['num'];
        $sql = "SELECT COUNT(*) as num  FROM zy_building_upgrade_record a LEFT JOIN zy_user b ON a.uid=b.uid WHERE $where";
        $count_result = $this->db->query($sql)->row_array();
        $data['count'] = $count_result['num'];
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $status = config_item('building_upgrade');

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_building_upgrade_record a,zy_user b','a.*,b.openid,b.nickname,b.game_lv', $where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as &$value){
            $res = $this->model->get_row('zy_building','is_upgrade',['type'=>$value['type'],'uid'=>$value['uid']]);

            $value['status'] = $res['is_upgrade'];
            $value['status'] = $status[$value['status']];
            $sql = "select `name` from zy_building_config WHERE type=?";
            $row = $this->db->query($sql,[$value['type']])->row_array();
            $value['name'] = $row['name'];
        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID','b.openid'=>'openid'];
        //后台访问日志
        $this->log_admin_model->logs('查询建筑升级记录',1);
        $this->load->view( $this->list_view, $data);
    }

    //列表
    function config_list(){
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
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
        $data['count'] = $this->model->table_count('zy_building_config ',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->table_lists('zy_building_config','*',$where, 'id DESC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['type' => '建筑类型'];

        //后台访问日志
        $this->log_admin_model->logs('查询建筑信息',1);

        $this->load->view('admin/building_config_list', $data);
    }


    //    添加
    function add(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view($this->add_view, $data);
    }

    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $sql = "select * from zy_building_config WHERE id=?";
        $value = $this->db->query($sql,[$id])->row_array();
        $data['value'] = $value;
        $this->load->view($this->add_view,$data);
    }

    //保存
    function save(){
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['updatetime'] = t_time();
            $this->model->table_update('zy_building_config',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['addtime'] = t_time();
            $this->model->table_insert('zy_building_config',$data);
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
        if(!permission('SYS_Building','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->table_delete('zy_building_config',$id);
            }
        } else {
            $this->model->delete('zy_building_config',$id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

}
