<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/*
 * 设置
 */
include 'Content.php';

class Setting extends Content
{
    function __construct()
    {
        $this->name = '通用设置';
        $this->control = 'setting';
        $this->model_name = 'setting_model';
        $this->list_view = 'setting'; // 列表页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/setting/');
        $this->load->model('admin/setting_model', 'model');
    }

    // 首页
    public function index()
    {
        if(!permission('SYS_Setting','read')) show_msg('没有操作权限！');
        $data['list'] = $this->model->get_list();

        $this->url_forward($this->baseurl . "/index");
        //后台访问日志
        $this->log_admin_model->logs('查询通用设置信息',1);
        $this->load->view('admin/'.$this->list_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
        if(!permission('SYS_Setting','write')){
            show_msg('没有操作权限！');
        }
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
//         if (empty($data['about'])) {
//             show_msg('不能为空');
//         }
        foreach ($data as $key => $value) {
            $this->model->set($key, $value);
        }
        //后台访问日志
        $this->log_admin_model->logs('添加通用设置信息',1);
        show_msg('保存成功！', $this->admin['url_forward']);
    }

}
