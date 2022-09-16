<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  醇化
 */

include_once 'Base.php';

class Aging extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/aging_model');
    }

    // 获取醇化室各个槽的状态
    public function lists()
    {
        $list = $this->aging_model->lists_status($this->uid);

        t_json($list);
    }

    // 开始醇化
    public function aging_start()
    {
        $aging_index = trim($this->input->post('aging_index'));
        $before_shopid = trim($this->input->post('shopid'));
        if ($aging_index=='' || !$before_shopid) t_error(1, '槽编号和商品id不能为空');

        $result = $this->aging_model->aging_start($this->uid, $aging_index, $before_shopid);

        t_json($result);
    }


    // 收取醇化过后的烟叶
    public function aging_gather()
    {
        $aging_index = trim($this->input->post('aging_index'));

        $result = $this->aging_model->aging_gather($this->uid, $aging_index);

        t_json($result);
    }

    //醇化加速
    function aging_jiasu(){
        $aging_index = trim($this->input->post('aging_index'));
        $result = $this->aging_model->aging_jiasu($this->uid,$aging_index);
        t_json($result);
    }

    function aaa(){
        $result = $this->aging_model->aaa($this->uid);
        t_json($result);
    }

}