<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 间谍
include_once 'Base.php';

class Jiandie extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/jiandie_model');
    }

    function zu_jiandie(){
        $result = $this->jiandie_model->zu_jiandie($this->uid);
        t_json($result);
    }

    // 开始雇佣一个
    function start()
    {
        $code = $this->input->post('code');

        $result = $this->jiandie_model->start($this->uid, $code);
        
        t_json($result);
    }

    // 驱赶间谍
    function clear()
    {
        $number = $this->input->post('number');
        if (!$number) t_error(1, '间谍编号不能为空');

        $result = $this->jiandie_model->clear($this->uid, $number);
        
        t_json($result);
    }
    
    // 我的收入列表
    function list_shouru()
    {
        $result = $this->jiandie_model->list_shouru($this->uid);
        t_json($result);
    }

    // 一键入库
    function to_store()
    {
        $result = $this->jiandie_model->to_store($this->uid);
        t_json($result);
    }

    // 收入测试
    function shouru_test()
    {
        $result = $this->jiandie_model->shouru('abc', 301);

        t_json($result);
    }

    //间谍租赁、放置、被放置状态信息
    function jd_status(){
        $result = $this->jiandie_model->jd_status($this->uid);
        t_json($result);
    }

    //定时查询有无被放置间谍
    function jd_query(){
        $result = $this->jiandie_model->jd_query($this->uid);
        t_json($result);
    }

    //解锁间谍系统
    public function unlock_jiandie(){
        $spend_type = $this->input->post('spend_type');
        if ($spend_type != 'ledou' && $spend_type != 'money') t_error(1, '参数不正确，请检查');
        $result = $this->jiandie_model->unlock_jiandie($this->uid, $spend_type);

        t_json($result);
    }


}