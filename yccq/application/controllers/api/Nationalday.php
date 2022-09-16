<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/9/5
 * Time: 12:53
 */
include_once 'Base.php';

class Nationalday extends Base{
    function __construct(){
        parent::__construct();
        $this->load->model('api/nationalday_model');
    }

    function prize_list(){
        $result = $this->nationalday_model->prize_list($this->uid);
        t_json($result);
    }

    function receive(){
        $result = $this->nationalday_model->receive($this->uid);
        t_json($result);
    }

    function test(){
        $this->nationalday_model->test();
    }
}