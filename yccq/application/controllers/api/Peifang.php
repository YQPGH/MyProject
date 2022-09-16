<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 配方研究所
include_once 'Base.php';

class Peifang extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/peifang_model');
    }

    // 建筑状态
    public function status()
    {
        $result = $this->peifang_model->status($this->uid);
        
        t_json($result);
    }

    // 开始合成
    public function start()
    {
        $peifang1 = intval($this->input->post('peifang1'));
        $peifang2 = intval($this->input->post('peifang2'));
        $peifang3 = intval($this->input->post('peifang3'));
        if (!$peifang1 || !$peifang2 || !$peifang3) t_error(1, '参数不能为空，请检查');

        $result = $this->peifang_model->start($this->uid, $peifang1, $peifang2, $peifang3);

        t_json($result);
    }

    //解锁调香研究所
    public function unlock_peifang(){
        $spend_type = $this->input->post('spend_type');
        if ($spend_type != 'ledou' && $spend_type != 'money') t_error(1, '参数不正确，请检查');
        $result = $this->peifang_model->unlock_peifang($this->uid, $spend_type);

        t_json($result);
    }


}