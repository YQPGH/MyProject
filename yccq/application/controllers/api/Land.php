<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 土地
include_once 'Base.php';

class Land extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/land_model');
    }
    
    // 获取我的土地状态
    public function lists()
    {
        $list = $this->land_model->lists_status($this->uid);

        t_json($list);
    }

    // 开始种植
    public function seed()
    {
        $land_id = intval($this->input->post('land_id'));
        $seed_shopid = intval($this->input->post('seed_shopid'));

        if (!$land_id || !$seed_shopid) t_error(1, '土地和种子id不能为空');
        
        $result = $this->land_model->seed($this->uid, $land_id, $seed_shopid);

        t_json($result);
    }


    // 收割
    public function gather()
    {
        $land_id = intval($this->input->post('land_id'));
        if (!$land_id) t_error(1, '土地id不能为空');

        $result = $this->land_model->gather($this->uid, $land_id);

        $return_arr['trees_number'] = $result['trees_number'];

        unset($result['trees_number']);
        if(count($result['suipian'])>0){
            $return_arr['suipian'][] = $result['suipian'];
        }else{
            $return_arr['suipian'] = array();
        }

        if($result['is_use']){
            unset($result['is_use']);
            unset($result['suipian']);
            $return_arr['success'] = array();
            $return_arr['false'][0] = $result;

        }else{
            unset($result['is_use']);
            unset($result['suipian']);
            $return_arr['success'][0] = $result;
            $return_arr['false'] = array();

        }

        t_json($return_arr);

    }

    //一键收割
    public function yi_jian_gather(){
        $result = $this->land_model->yi_jian_gather($this->uid);
        
        t_json($result);
    }


    // 升级土地
    public function upgrade()
    {
        $land_id = intval($this->input->post('land_id'));
        if (!$land_id) t_error(1, '土地id不能为空');

        $result = $this->land_model->upgrade($this->uid, $land_id);

        t_json($result);
    }

    function my_delete(){
        $land_id = intval($this->input->post('land_id'));
        if (!$land_id) t_error(1, '土地id不能为空');
        $result = $this->land_model->my_delete($this->uid, $land_id);
        if($result){
            t_json();
        }else{
            t_error();
        }
    }

    function seed_jiasu(){
        $land_id = intval($this->input->post('land_id'));
        $result = $this->land_model->seed_jiasu($this->uid,$land_id);
        t_json($result);
    }

}