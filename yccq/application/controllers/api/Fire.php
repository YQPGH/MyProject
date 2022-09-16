<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *放火
 *
 */
include_once 'Base.php';

class Fire extends Base{
    function  __construct(){
        parent::__construct();
        $this->load->model('api/fire_model');
    }

    //开始放火
    public function start(){
        $code = $this->input->post('code');
        if(!$code) t_error(1, 'code不能为空');
        $result = $this->fire_model->start($this->uid,$code);
        t_json($result);
    }

    //灭火
    public function outfire(){
        $number = $this->input->post('number');
        if (!$number) t_error(1, '虫子编号不能为空');
        $result = $this->fire_model->outfire($this->uid,$number);
        t_json($result);
    }

    //被放火状态信息
    function fire_status(){

        $result = $this->fire_model->fire_status($this->uid);
        t_json($result);
    }

    //好友被放火状态信息
    function friend_fire_status(){
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');
        $result = $this->fire_model->friend_fire_status($this->uid, $code);
        t_json($result);
    }

    //好友是否烘烤中
    function friend_bake(){
        $code = $this->input->post('code');
        if (!$code) t_error(1, 'code不能为空');
        $result = $this->fire_model->friend_bake($this->uid, $code);
        t_json($result);
    }
    function test(){
        $this->fire_model->jiasu($this->uid);
    }
}