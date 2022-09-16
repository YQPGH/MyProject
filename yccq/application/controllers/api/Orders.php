<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 订单
include_once 'Base.php';

class Orders extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/orders_model');
    }

    // 列表
    public function lists()
    {
        $result = $this->orders_model->list_all($this->uid);

        t_json($result);
    }

    // 完成一个订单
    public function complete()
    {
        $order_index = intval($this->input->post('order_index'));
        if ($order_index<0 || $order_index>5) t_error(1, '订单位置编号有误');

        // 判断今日订单总数
        $order_total = model('setting_model')->get('order_total');
        if($this->orders_model->today_completed($this->uid) >= $order_total){
            t_error(2, '今日完成订单数已达20单，请明天再来');
        }

        $value = $this->orders_model->complete($this->uid, $order_index);

        t_json($value);
    }

    //删除订单
    public function delete_order(){
        $order_index = intval($this->input->post('order_index'));
        if ($order_index<0 || $order_index>5) t_error(1, '订单位置编号有误');
        $result = $this->orders_model->delete_order($this->uid, $order_index);
        t_json($result);
    }

    //刷新订单
    public function refresh(){
        $order_index = intval($this->input->post('order_index'));
        if ($order_index<0 || $order_index>5) t_error(1, '订单位置编号有误');
        $result = $this->orders_model->refresh($this->uid,$order_index);
        t_json($result);
    }

    //系统刷新订单
    public function sys_refresh(){
        $order_index = intval($this->input->post('order_index'));
        if ($order_index<0 || $order_index>5) t_error(1, '订单位置编号有误');
        $result = $this->orders_model->sys_refresh($this->uid,$order_index);
        t_json($result);
    }

    //查询订单是否完成
    public function is_order_completed(){
        $order_index = intval($this->input->post('order_index'));
        if ($order_index<0 || $order_index>5) t_error(1, '订单位置编号有误');
        $result = $this->orders_model->is_order_completed($this->uid,$order_index);
        t_json($result);
    }

    //概率测试
    public function testRank(){
        $result = $this->orders_model->testRank($this->uid);
        t_json($result);
    }


}