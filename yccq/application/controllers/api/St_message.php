<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 我的物品
include_once 'Base.php';

class St_message extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/St_message_model');
    }

    //获取实体商品（实体烟、打火机等）
    public function lists_st(){
        $page = intval($this->input->post('page'));
        $value = $this->St_message_model->lists_st($this->uid,$page);
        t_json($value);
    }







}