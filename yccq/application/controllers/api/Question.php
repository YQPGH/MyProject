<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日问题
include_once 'Base.php';

class Question extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/question_model');
    }

    // 获取题目
    public function lists()
    {
        $result = $this->question_model->lists_today($this->uid);

        t_json($result);
    }

    // 回答，提交答案
    public function answer()
    {
        $id = intval($this->input->post('id'));
        $option = intval($this->input->post('option'));
        if (!$id || !$option) t_error(1, '参数不能为空');

        $result = $this->question_model->answer($this->uid, $id, $option);

        t_json($result);
    }

    // 回答，提交答案
    public function set()
    {
        $result = $this->question_model->set_today();
        t_json($result);
    }
    
    // 获取结果和奖品信息
    public function result()
    {
        $result = $this->question_model->result($this->uid);
        t_json($result);
    }


}