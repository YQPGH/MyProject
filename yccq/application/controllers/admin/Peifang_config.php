<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 新闻资讯控制器
 */
include_once 'Content.php';

class Peifang_config extends Content
{
    function __construct()
    {
        $this->name = '配方合成概率配置';
        $this->control = 'Peifang_config';
        $this->list_view = 'peifang_config_list'; // 列表页
        $this->add_view = 'peifang_config_add'; // 添加页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/Peifang_config/');
        $this->load->model('admin/Peifang_config_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Peifang_config','read')) show_msg('没有操作权限！');
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
        $data['count'] = $this->model->count($where);
        $data['pages'] = $this->page_html($url_forward, $data['count']);
        $this->url_forward($url_forward . '&per_page=' . $offset);

        // 列表数据
        $list = $this->model->lists('*', $where, 'id ASC', $this->per_page, $offset);

        $data['list'] = ($list);
        // 搜索
        $data['fields'] = ['grade_before' => '合成前等级', 'grade_after' => '合成后等级'];
        //后台访问日志
        $this->log_admin_model->logs('查询配方研究配置信息',1);
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add()
    {
        if(!permission('SYS_Peifang_config','write')){
            show_msg('没有操作权限！');
        }
        $value['catid'] = intval($_REQUEST['catid']);
        $data['value'] = $value;

        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 编辑
    public function edit()
    {
        if(!permission('SYS_Peifang_config','write')){
            show_msg('没有操作权限！');
        }
        $id = $this->input->get('id');
        $id = check_id($id);
        
        // 这条信息
        $value = $this->model->row($id);
        $data['value'] = $value;

        $this->load->view('admin/'. $this->add_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Peifang_config','write')){
            show_msg('没有操作权限！');
        }

        $id = $this->input->post('id');
        $id = check_id($id);
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
        if (empty($data['grade_before'])||empty($data['grade_after'])||empty($data['rate'])) show_msg('参数不能为空');

        if ($id) { // 修改 ===========
            $data['update_time'] = t_time();
            $this->model->update($data, $id);
            //后台访问日志
            $this->log_admin_model->logs('修改配方研究配置信息',1);
            show_msg('修改成功！', $this->admin['url_forward']);
        } else { // ===========添加 ===========
            $data['add_time'] = t_time();
            $this->model->insert($data);
            //后台访问日志
            $this->log_admin_model->logs('新增配方研究配置信息',1);
            show_msg('添加成功！', 'index');
        }
    }


    public function delete()
    {
        if(!permission('SYS_Peifang_config','del')){
            show_msg('没有操作权限！');
        }
        parent::delete();
    }

}
