<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *活动管理
 *
 */
include_once 'Content.php';
class Other_activity extends Content{

    function __construct(){
        $this->name='其他平台活动管理';
        $this->control = 'Other_activity';
        $this->list_view = 'other_activity_list';
        $this->add_view = 'other_activity_add';
        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/other_activity/');
        $this->load->model('admin/other_activity_model','model');

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
        $data['count'] = $this->model->table_count('zy_other_activity a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.shopid=b.shopid' ;//获取玩家昵称
        $list = $this->model->table_lists('zy_other_activity a,zy_shop b','a.*,b.name', $where, 'id DESC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.level' => '档次'];

        //后台访问日志
        $this->log_admin_model->logs('查询其他活动列表信息',1);

        $this->load->view('admin/'.$this->list_view, $data);
    }

    //    添加
    function add(){
        if(!permission('SYS_Other_Activity','write')){
            show_msg('没有权限操作！');
        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view('admin/'.$this->add_view, $data);
    }
    //编辑
    function edit(){
        if(!permission('SYS_Other_Activity','write')){
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
        if(!permission('SYS_Other_Activity','write')){
            show_msg('没有权限操作！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (empty($data['level'])) show_msg('档次不能为空');
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