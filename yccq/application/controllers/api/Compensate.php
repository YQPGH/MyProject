<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  醇化
 */

include_once 'Base.php';

class Compensate extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Compensate_model');
    }

    // 获取补偿列表
    public function lists()
    {
        $list = $this->Compensate_model->compensateLists($this->uid);

        t_json($list);
    }

    public function getCompensate(){
        $id = intval($this->input->post('id'));
        if($id<0) t_error(1, '参数有误');
        $list = $this->Compensate_model->getCompensate($this->uid,$id);
        t_json($list);
    }


}