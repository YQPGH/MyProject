<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/*
 *  设置
 */
include 'Content.php';

class Xp_config extends Content
{
    function __construct()
    {
        $this->name = '经验值设置';
        $this->control = 'xp_config';
        $this->model_name = 'xp_config_model';
        $this->list_view = 'xp_config'; // 列表页

        parent::__construct();
        $_SESSION['nav'] = 3;
        $this->baseurl = site_url('admin/xp_config/');
        $this->load->model('admin/xp_config_model', 'model');
    }

    // 首页
    public function index()
    {
        $data['list'] = $this->model->get_list();

        $this->url_forward($this->baseurl . "/index");
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 保存 添加和修改都是在这里
    public function save()
    {
//        $data = trims($_POST['value']);
        $data = $this->security->xss_clean(trims($this->input->post('value')));
//         if (empty($data['about'])) {
//             show_msg('不能为空');
//         }
        foreach ($data as $key => $value) {
            $this->model->set($key, $value);
        }

        show_msg('保存成功！', $this->admin['url_forward']);
    }

}
