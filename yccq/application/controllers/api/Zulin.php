<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 我的物品
include_once 'Base.php';

class Zulin extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/zulin_model');
    }

    // 列表
    public function lists()
    {
        $list = $this->zulin_model->list_all($this->uid);
        
        t_json($list);
    }

   public function zu(){
       $number = $this->input->post('number');
       $result = $this->zulin_model->zu($this->uid,$number);
       t_json($result);
  }









}