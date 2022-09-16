<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *
 */
include_once 'Content.php';

class Headframe extends Content
{
    function __construct()
    {
        $this->name = '头像框管理';
        $this->control = 'headframe';
        $this->list_view = 'headframe_list'; // 列表页
        $this->add_view = 'headerframe_add'; //添加页

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/headframe/');
        $this->load->model('admin/headframe_model', 'model');

    }

    // 首页
    public function index()
    {
        $_SESSION['nav'] = 6;
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
        $lv_list  = $this->model->lists_sql("select type2 from zy_headerframe WHERE `type1`='game_lv'");
        $array = implode(',',array_column($lv_list, 'type2'));
        $where.="and a.game_lv in({$array})";
        $data['count'] = $this->model->table_count('zy_gamelv_prize_record a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);

        $this->url_forward($url_forward . '&per_page=' . $offset);
        $where.= "and b.type1='game_lv' and b.type2=a.game_lv";
        // 列表数据
        $list = $this->model->table_lists('zy_gamelv_prize_record a,zy_headerframe b','a.*,b.type2', $where,'a.id DESC', $this->per_page, $offset);

        foreach ($list as &$value) {
            $row = $this->model->get_row('zy_user','nickname',['uid'=>$value['uid']]);
            $value['nickname'] = $row['nickname'];
            $value['num'] = 1;
            $value['frame'] = $value['game_lv'].'级头像框';
        }
        $data['list'] = $list;
        // 搜索
        $data['fields'] = [

            'uid' => '用户id',
           ];

        //后台访问日志
        $this->log_admin_model->logs('查询用户头像框列表',1);

        $this->load->view('admin/' . $this->list_view, $data);
    }

    //列表
    function frame_list(){

        $_SESSION['nav'] = 7;
        $url_forward = $this->baseurl . '/frame_list?';

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
        $where.= "and a.type1='game_lv'";
        // URL及分页
        $offset = intval($_GET['per_page']);
        $data['count'] = $this->model->table_count('zy_headerframe a ',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->table_lists('zy_headerframe a',',a.*',$where, 'id DESC', $this->per_page, $offset);

//        foreach($list as &$value){
//            $row = $this->model->get_row('zy_shop','name',['shopid'=>$value['shopid']]);
//            $value['shop_name'] = $row['name'];
//        }

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.type2' => '等级'];

        //后台访问日志
        $this->log_admin_model->logs('查询头像框配置信息',1);

        $this->load->view('admin/header_config_list', $data);
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
        $value = $this->model->get_row('zy_headerframe','*',['type1'=>'game_lv','id'=>$id]);
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
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加成功',1);
            show_msg('添加成功！', 'frame_list');
        }

    }

    public function delete(){
        parent::delete();
    }

}
