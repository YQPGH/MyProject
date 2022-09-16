<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 烟叶 烘烤 醇化等
include_once 'Base.php';

class Yanye extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/yanye_model');
    }

    // 烘烤开始
    public function bake_start()
    {
        $shopid = $this->input->post('shopid');
        if (!$shopid) t_error(1, '参数不能为空');

        $result = $this->yanye_model->bake_start($this->uid, $shopid);
        t_json($result);
    }

    // 烘烤室状态
    public function bake_jiasu()
    {
        $value = $this->yanye_model->bake_jiasu($this->uid);
        
        t_json($value);
    }
    
    // 烘烤收取
    public function bake_gather()
    {
        $result = $this->yanye_model->bake_gather($this->uid);

        t_json($result);
    }

    //点击“停火”按钮后，直接获取烘烤后的烟叶
    public function save_gather()
    {
        $result = $this->yanye_model->bake_gather($this->uid);

        t_json($result);
    }

    // 醇化开始
    public function aging_start()
    {
        $shopid = $this->input->post('shopid');
        if (!$shopid) t_error(1, '参数不能为空');

        $result = $this->yanye_model->aging_start($this->uid, $shopid);

        t_json($result);
    }

    // 醇化室状态
//    public function aging_status()
//    {
//        $value = $this->yanye_model->aging_status($this->uid);
//
//        t_json($value);
//    }


    // 醇化收取
    public function aging_gather()
    {
        $this->yanye_model->aging_gather($this->uid);

        t_json();
    }

    //醇化室升级
    public function upgrade_aging()
    {
        $result = $this->yanye_model->upgrade_aging($this->uid);

        t_json($result);
    }

    //醇化加速
    public function chun_jiasu()
    {
        $result = $this->yanye_model->chun_jiasu($this->uid);

        t_json($result);
    }

    // 烟叶状态改变 测试用
//    public function update_status()
//    {
//        $status = $this->input->post('status');
//        $result = $this->yanye_model->update(['status'=>$status], ['uid'=>$this->uid]);
//
//        t_json($result);
//    }


}