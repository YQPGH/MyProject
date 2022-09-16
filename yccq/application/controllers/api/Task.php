<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 每日任务
include_once 'Base.php';

class Task extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/task_model');
    }

    // 今日任务列表
    function lists()
    {
        $list = $this->task_model->list_all($this->uid);
        
        t_json($list);
    }

    // 今日扫码任务列表
    function scan_lists()
    {
        $list = $this->task_model->scan_list($this->uid);

        t_json($list);
    }

    // 领取
    function get_task_prize()
    {
        $id = $this->input->post('id'); //任务id
        $result = $this->task_model->get_task_prize($this->uid, $id);
        t_json($result);
    }


}