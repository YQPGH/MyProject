<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 好友聊天管理
 */
include_once 'Content.php';

class Chat extends Content
{
    function __construct()
    {
        $this->name = '好友聊天详情';
        $this->control = 'chat';
        $this->list_view = 'admin/chat_list'; // 列表页
        $this->add_view = 'admin/chat_config_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 6;
        $this->baseurl = site_url('admin/chat/');
        $this->load->model('admin/chat_model', 'model');
    }
    
    // 首页
    public function index()
    {
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');
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
        $query_count['num'] = $this->model->count($where);
        $data['count'] = $query_count['num'];

        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.uid=b.uid' ;//获取玩家昵称
        $list = $this->model->table_lists('chat_detail a,zy_user b','a.*,b.openid,b.nickname', $where, 'a.id DESC', $this->per_page, $offset);
        $status = ['未读','已读'];
        foreach($list as &$value){

            $row = $this->db->query("select nickname from zy_user where uid=?",[$value['friend_uid']])->row_array();
            $value['friend_name'] = $row['nickname'];
            $value['is_read'] = $status[$value['is_read']];
        }

        $data['list'] = $list;
        // 搜索
        $data['fields'] = ['a.uid' => '用户ID','b.openid'=>'openid'];
        //后台访问日志
        $this->log_admin_model->logs('查询好友聊天记录',1);
        $this->load->view( $this->list_view, $data);
    }

    //列表
    function disbale_list(){
        $this->name = '好友禁言';
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . 'disbale_list?';

        // 查询条件
        $where = ' a.status=1';

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
        $data['count'] = $this->model->table_count('zy_friend a',$where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);
        $where .= ' and a.uid=b.uid';
        $status = ['正常','已禁'];
        // 列表数据
        $list = $this->model->table_lists('zy_friend a,zy_user b','a.id,a.status,a.update_time,a.uid,a.friend_uid,b.openid,b.nickname',$where, 'a.id DESC', $this->per_page, $offset);
        foreach($list as &$value)
        {
            $user = $this->db->query("select openid  from zy_user where uid=?",[$value['friend_uid']])->row_array();
            $value['friend_openid'] = $user['openid'];
            $value['status']  = $status[$value['status']];
        }
        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['a.openid' => 'openID','a.uid' => '用户id'];

        //后台访问日志
        $this->log_admin_model->logs('查询聊天信息',1);

        $this->load->view('admin/chat_disbalelist', $data);
    }


    /**
     * 更新禁言状态
     */
    function update_status()
    {
        $id = intval($this->input->post('id'));
        if($id)
        {

            $this->model->table_update('zy_friend',['status' => 0,'update_time' => t_time()],['id' => $id]);

            t_json($id);
        }
        else
        {
          t_error(1,'获取信息失败');
        }

    }

    //    添加
    function add(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;
        $this->load->view($this->add_view, $data);
    }

    //编辑
    function edit(){
        $_SESSION['nav'] = 7;
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $sql = "select * from zy_chat_config WHERE id=?";
        $value = $this->db->query($sql,[$id])->row_array();
        $data['value'] = $value;
        $this->load->view($this->add_view,$data);
    }

    //保存
    function save(){
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');
        $id = $this->input->post('id');
        $id = check_id($id);
        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if($id){  //修改
            $data['updatetime'] = t_time();
            $this->model->table_update('zy_chat_config',$data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改成功',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        }else{  //添加
            $data['addtime'] = t_time();
            $this->model->table_insert('zy_chat_config',$data);
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
        if(!permission('SYS_chat','read')) show_msg('没有操作权限！');
        $id = $this->input->get('id');
        $id = check_id($id);
        $id_arr = $this->input->post('delete');

        if ($id) {
            //当前用户不能删除自身
            if ($id == $_SESSION['admin']['id']){
                show_msg('非法操作！', $this->admin['url_forward']);
            }else{
                $this->model->table_delete('zy_chat_config',$id);
            }
        } else {
            $this->model->delete('zy_chat_config',$id_arr);
        }
        //后台访问日志
        $this->log_admin_model->logs('删除',1);
        show_msg('删除成功！', $this->admin['url_forward']);
    }

}
