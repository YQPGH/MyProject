<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2019/10/9
 * Time: 17:06
 */

//建筑升级
include_once 'Base.php';
class Building extends Base{
    function __construct(){
        parent::__construct();
        $this->load->model('api/building_model');
    }

    function lists(){

        $result = $this->building_model->building_lists($this->uid);
        t_json($result);
    }

    function upgrade(){
        $number = $this->input->post('number');
        if(empty($number) || (!is_numeric($number))) t_error(1,'升级失败');
        $result = $this->building_model->upgrade($this->uid,$number);
        t_json($result);
    }

    function change_interface(){
        $number = $this->input->post('number');
        if(!is_numeric($number)) t_error(1,'更换失败');
        $status = $this->input->post('status');

        $result = $this->building_model->init_interface($this->uid,$number,$status);
        t_json($result);
    }

    function task_receive(){

        $result = $this->building_model->task_receive($this->uid);
        t_json($result);
    }

    //是否已升级
//    function query_upgrade(){
//        $result = $this->building_model->query_upgrade($this->uid);
//        t_json($result);
//    }

    function test(){
//        $result = $this->building_model->init_data($this->uid);
        $result = $this->stat_model->shop_set();
        t_json($result);
    }
}