<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  烘烤
 */

include_once 'Base.php';

class Bake extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/bake_model');
    }
    
    // 获取烘烤室各个槽的状态
    public function lists()
    {
        $list = $this->bake_model->lists_status($this->uid);

        t_json($list);
    }

    // 开始烘烤
    public function bake_start()
    {
        $bake_index = trim($this->input->post('bake_index'));
        $before_shopid = trim($this->input->post('shopid'));
        if ($bake_index=='' || !$before_shopid) t_error(1, '槽编号和商品id不能为空');
        
        $result = $this->bake_model->bake_start($this->uid, $bake_index, $before_shopid);

        t_json($result);
    }


    // 收取烘烤过后的烟叶
    public function bake_gather()
    {
        $bake_index = trim($this->input->post('bake_index'));

        $result = $this->bake_model->bake_gather($this->uid, $bake_index);

        t_json($result);
    }

    function bake_jiasu(){
        $bake_index = trim($this->input->post('bake_index'));
        $result = $this->bake_model->bake_jiasu($this->uid,$bake_index);
        t_json($result);
    }

}