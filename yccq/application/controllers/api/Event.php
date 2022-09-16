<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 事件中心
include_once 'Base.php';

class Event extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/event_model');
        $this->load->model('api/user_model');
    }

    // 所有列表
    public function lists()
    {
        $list = $this->event_model->lists('*', ['uid' => $this->uid, 'status' => 0]);
        $result = [];
        foreach ($list as $key=>&$value) {
            // 烘烤事件显示 要>=8分钟 小于等于结束时间
            if ($value['type1'] == 2) {
                $status = model('status_model')->detail($this->uid);
                if((time() - strtotime($status['bake_start'])) > 60 * 8 && t_time()<$status['bake_stop']){
                    $result[] = $list[$key];
                }

            } else {
                $result[] = $list[$key];
            }
        }

        t_json($result);
    }

    // 忽略一个事件
    public function cancel()
    {
        $id = intval($this->input->post('id'));
        if (!$id) t_error(1, 'id不能为空');

        $result = $this->event_model->update(['status' => 1], ['id' => $id, 'uid' => $this->uid]);
        if ($result)
            t_json();
        else
            t_error(2, '发生意外了，请稍后再来');
    }

    // 处理一个事件
    public function change()
    {
        $id = intval($this->input->post('id'));
        if (!$id) t_error(1, 'id不能为空');

        $result = $this->event_model->delete(['id' => $id, 'uid' => $this->uid]);
        // 奖励 100银元
        $this->user_model->money($this->uid, 100);
        if ($result)
            t_json();
        else
            t_error(2, '处理错误，请稍后再来');
    }


}