<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 加工包装
include_once 'Base.php';

class Process extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/process_model');
    }

    //获取加工厂各个槽的状态
    public function lists()
    {
        $list = $this->process_model->lists_status($this->uid);

        t_json($list);
    }

    // 开始加工
    public function process_start()
    {
        $process_index = trim($this->input->post('process_index'));
        $before_shopid = trim($this->input->post('shopid'));
        if ($process_index=='' || !$before_shopid) t_error(1, '槽编号和商品id不能为空');

        $result = $this->process_model->process_start($this->uid, $process_index, $before_shopid);

        t_json($result);
    }


    // 收取加工过后的烟叶
    public function process_gather()
    {
        $process_index = trim($this->input->post('process_index'));

        $result = $this->process_model->process_gather($this->uid, $process_index);

        t_json($result);
    }

    //加工加速
    function process_jiasu(){
        $process_index = trim($this->input->post('process_index'));
        $result = $this->process_model->process_jiasu($this->uid,$process_index);
        t_json($result);
    }


}