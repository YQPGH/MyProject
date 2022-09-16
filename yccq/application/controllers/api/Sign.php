<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日签到
include_once 'Base.php';

class Sign extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/sign_model');
    }

    // 开始签到
    public function sign()
    {
        $result = $this->sign_model->sign($this->uid);
        
        t_json($result);
    }

    // 我的签到列表
    public function lists()
    {
        $result = $this->sign_model->list_my($this->uid);
        
        t_json($result);
    }

    // 我的签到列表
    public function reward()
    {
        $result = $this->sign_model->reward($this->uid);

        t_json($result);
    }



}