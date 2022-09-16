<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日登录
include_once 'Base.php';

class Loginprize extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/loginprize_model');
    }


    function login_activity(){
        $result = $this->loginprize_model->login_activity($this->uid);
        t_json($result);
    }

    // 开始登录 领取奖励
    public function login()
    {
        $result = $this->loginprize_model->login($this->uid);
        
        t_json($result);
    }



}