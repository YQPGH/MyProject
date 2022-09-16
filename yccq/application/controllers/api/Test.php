<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 用户的信息
include_once 'Base.php';

class Test extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/user_model');
    }

    public function index(){
//        echo phpinfo();
    }

    public function curlRequire(){
        $this->load->model('api/Ld_test_model');
        $result = $this->Ld_test_model->curl();
        print_r($result);
    }

    public function curlData(){
        $data = array('a'=>'aaaaa');
        t_json($data,0,'curl成功');
    }

    public function getYD(){
        //$openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        //$openid = 'ocoMKt4wvPEJ05tMacR7V646eGS8';
        $this->load->model('api/Ld_test_model');
        //$openid = $this->input->post('openid');
        $openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        $result = $this->Ld_test_model->getYD($openid);
        print_r($result);
    }

    public function rechargeDY(){
        $this->load->model('api/Ld_test_model');
        //$smokeBeans = $this->input->post('number');
        //$openid = $this->input->post('openid');
        //$openid = 'oREekjrPFnTPQXOGA5GgYoH-_mEQ';
        $smokeBeans = 23;
        $openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        $desc = '烟草传奇补偿';
        $result = $this->Ld_test_model->rechargeDY($smokeBeans, $openid, $desc);
        print_r($result);
    }

    public function consumeYD(){
        $this->load->model('api/Ld_model');
        $smokeBeans = -1;
        $openid = 'oREekjnJ9TXGXdo7Dq6XHb9zgFqA';
        $desc = '烟草传奇公测消耗';
        $result = $this->Ld_model->consumeYD($smokeBeans, $openid, $desc);
        print_r($result);
    }



}
