<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 转盘抽奖
 */

include_once 'Base.php';
class Turntable extends Base{
    function  __construct(){
        parent::__construct();
        $this->load->model('api/turntable_model');
    }

    function draw_times(){
        $result = $this->turntable_model->draw_times($this->uid);
        t_json($result);
    }

    function start(){
        $result = $this->turntable_model->start($this->uid);
        t_json($result);
    }
}