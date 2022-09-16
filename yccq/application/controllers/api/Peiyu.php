<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 种子培育中心
include_once 'Base.php';

class Peiyu extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/peiyu_model');
    }

    // 开始培育
    public function start()
    {
        $number = intval($this->input->post('number'));
        $yanye1 = intval($this->input->post('yanye1'));
        $yanye2 = intval($this->input->post('yanye2'));
        if (!$number || !$yanye1 || !$yanye2) t_error(1, '参数不能为空，请检查');

        $result = $this->peiyu_model->start($this->uid, $number, $yanye1, $yanye2);

        t_json($result);
    }

    // 完成收取种子
    public function gather()
    {
        $number = intval($this->input->post('number'));
        if (!$number) t_error(1, '参数不能为空，请检查');
        
        $result = $this->peiyu_model->gather($this->uid, $number);

        t_json($result);
    }

    // 培育室状态
    public function status()
    {
        $result = $this->peiyu_model->status($this->uid);

        t_json($result);
    }

    //扩展培育槽
    public function upgrade(){
        $result = $this->peiyu_model->upgrade($this->uid);

        t_json($result);
    }

    //解锁种子培育中心
    public function unlock_peiyu(){
        $spend_type = $this->input->post('spend_type');
        if ($spend_type != 'ledou' && $spend_type != 'money') t_error(1, '参数不正确，请检查');
        $result = $this->peiyu_model->unlock_peiyu($this->uid, $spend_type);

        t_json($result);
    }


}