<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 9:41
 */
include_once 'Content.php';
class Scrape extends Content{

    function __construct(){
        $this->name = '新年礼物';
        $this->control = 'scrape';
        $this->list_view = 'admin/scrape_name_list'; //用户信息列表页

        parent ::__construct();
        $_SESSION['nav'] = 8;
        $this->baseurl = site_url('admin/scrape/');
        $this->load->model('admin/scrape_model','model');
    }



    function name_list()
    {
        if(!permission('SYS_Scrape_Name_list','read')) show_msg('没有操作权限！');

        $url_forward = $this->baseurl . '/name_list?';
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
        $data['count'] = $this->model->table_count('zy_scrape_message a','is_out=1');
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $where .= 'AND a.is_out=1 AND a.uid=b.uid';
        $list = $this->model->table_lists('zy_scrape_message a,zy_user b','a.shop_name name,a.add_time,a.truename,a.address,b.openid,b.nickname', $where, 'a.add_time DESC', $this->per_page, $offset);
        foreach($list as &$value)
        {
            $value['address'] = str_replace(",",'',$value['address']);
            $value['add_time'] = date("Y-m-d H:i:s",$value['add_time']);

        }

        $data['list'] = ($list);

        // 搜索
        $data['fields'] = ['b.openid' => 'openID'];
        //后台访问日志
        $this->log_admin_model->logs('查询新年礼物名单',1);

        $this->load->view($this->list_view, $data);
    }

    // 添加
    public function add()
    {
        $_SESSION['nav'] = 7;
        if(!permission('SYS_scrape','write')){
            show_msg('没有操作权限！');
        }
        $value['catid'] = intval($_REQUEST['catid']);

        $data['value'] = $value;

        $this->load->view($this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        $_SESSION['nav'] = 7;
        if(!permission('SYS_scrape','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);

        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view( $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {

        if(!permission('SYS_scrape','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->post('id');
        $id = check_id($id);

        $data = $this->security->xss_clean(trims($this->input->post('value')));


        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改奖励信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);

        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $data['update_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('添加奖励信息',1);
            show_msg('添加成功！', 'scrape_config');
        }
    }


    public function delete()
    {

        parent::delete();
    }


}
