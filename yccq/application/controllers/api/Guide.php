<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 新手指引
include_once 'Base.php';

class Guide extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/guide_model');
    }

    // 用户状态
    public function status()
    {
        $row = $this->guide_model->status($this->uid);

        t_json($row);
    }

    // 用户状态保存
    public function update()
    {
        $step1 = $this->input->post('step1');
        $step1 = intval($step1);
        $step2 = $this->input->post('step2');
        $step2 = intval($step2);
        $result = $this->guide_model->update_step($this->uid, $step1, $step2);

        t_json($result);
    }

    // 关闭提示
    public function close_tips()
    {
        $building = $this->input->post('building');
        if(!$building) t_error(1,'错误');

        $result = $this->guide_model->close_tips($this->uid, $building);

        t_json($result);
    }


}